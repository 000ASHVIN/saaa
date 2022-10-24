<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Ticket;
use App\AuthCode;
use App\Billing\Invoice;
use App\Billing\InvoiceRepository;
use App\Billing\Payment;
use App\Billing\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\SendAccountStatementJOb;
use App\Note;
use App\Repositories\CreditMemo\CreditMemoRepository;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Subscriptions\Models\Plan;
use App\Users\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;
use App\Video;
use App\Models\Course;
use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use App\AppEvents\DietaryRequirement;
use App\AppEvents\Extra;
use App\AppEvents\Date;
use App\InvoiceOrder;

class InvoicesController extends Controller
{
    private $creditMemoRepository, $sendInvoiceRepository, $invoiceRepository;

    /**
     * InvoicesController constructor.
     * @param CreditMemoRepository $creditMemoRepository
     * @param SendInvoiceRepository $sendInvoiceRepository
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(CreditMemoRepository $creditMemoRepository, SendInvoiceRepository $sendInvoiceRepository, InvoiceRepository $invoiceRepository)
    {
        $this->creditMemoRepository = $creditMemoRepository;
        $this->sendInvoiceRepository = $sendInvoiceRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function getCancel(Request $request, $id)
    {
        $this->validate($request, ['reason' => 'required']);
        $invoice = Invoice::with(['transactions', 'items', 'ticket'])->find($id);

        // Create Credit note
        try {
            DB::transaction(function () use ($invoice, $request) {
                $transaction = $invoice->transactions()->create([
                    'user_id' => $invoice->user->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'credit',
                    'display_type' => 'Credit Note',
                    'status' => 'Closed',
                    'category' => $invoice->type,
                    'amount' => $invoice->balance,
                    'ref' => $invoice->reference,
                    'method' => 'Void',
                    'description' => "Invoice #{$invoice->reference} cancellation",
                    'tags' => "Cancellation",
                    'date' => Carbon::now()
                ]);

                // Set Cancelled on Invoice
                $invoice->cancelled = 1;
                $invoice->status = 'credit noted';
                $invoice->save();


                // Create new entry for credit memo
                $this->creditMemoRepository->store($transaction);

                $note = new Note([
                    'type' => 'general',
                    'description' => $request->reason,
                    'logged_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                ]);

                $invoice->user->notes()->save($note);

                // Remove Ticket for invoice.
                if ($invoice->ticket) {
                    $invoice->ticket->delete();
                }
                alert()->success('Invoice Cancelled Successfully', 'Success');
            });

        } catch (\Exception $e) {
            alert()->error('Something went wrong', 'Contact Dev');
        }

        return back()->withInput(['tab' => 'user_invoices']);
    }

    public function getPaymentDelete(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        if($transaction){
            $transaction->delete();
            $invoice = $transaction->invoice;

            $has_cancelation_transactions = count($invoice->fresh()->transactions()->where('tags', 'Cancellation')->get()) > 0;
            if(!$has_cancelation_transactions) {
                $invoice->cancelled = 0;
                $invoice->save();
            }

            if($invoice->fresh()->balance > 0)
            {
                // Update Invoice Balance
                $invoice->paid = false;
                $invoice->status = 'unpaid';
                $invoice->date_settled = null;
                $invoice->balance = $invoice->balance + $transaction->amount;
                $invoice->save();
            }

            if($invoice->type == 'subscription')
            {
                $invoice->updateSubscriptionOverdueStatus();
            }

            $this->invoiceRepository->removeNotePayment($request, $transaction);
        }

        alert()->success('Payment Deleted Successfully', 'Success');
        return back()->withInput(['tab' => 'user_invoices']);
    }

    public function getAllocate($id)
    {
        $invoice = Invoice::with(['transactions', 'items'])->find($id);
        // $this->invoiceRepository->trySettlement($invoice);
        return view('admin.invoices.allocate', compact('invoice'));
    }

    public function allocateToSystem($id)
    {
        $invoice = Invoice::find($id);
        if($invoice) {
            $invoice->updateSalesPerson('system');
        }
        alert()->success('Invoice allocated to system successfully.', 'Success');
        return back();
    }

    public function createSubscriptionInvoice(Request $request, InvoiceRepository $invoiceRepository)
    {
        $this->validate($request, ['date' => 'required']);
        $invoice = $invoiceRepository->createSubscriptionInvoice(User::find($request->user_id), Plan::find($request->plan_id));
        $this->sendInvoiceRepository->sendInvoice(User::find($request->user_id), $invoice->fresh());
        $invoiceRepository->changeInvoiceDateTo($invoice, Carbon::parse($request->date));

        alert()->success('Invoice Created Successfully', 'Success');
        return redirect()->back()->withInput(['tab' => 'panel_create_new_invoice']);
    }

    public function createCourseInvoice(Request $request, InvoiceRepository $invoiceRepository)
    {
        $this->validate($request, [
            'course' => 'required',
            'enrollment_option' => 'required',
            'date' => 'required'
            ]
        );
        $user = User::find($request->user_id);
        $course = Course::find($request->course);
        $plan_id = $request->enrollment_option == 'monthly' ? $course->monthly_plan_id : $course->yearly_plan_id;
        $plan = Plan::find($plan_id);
        if($plan) {
            $invoice = $invoiceRepository->createCourseInvoice($user, $course, $request);
            if($course->exclude_vat == 1){
                $invoice->vat_rate = 0;
                $invoice->save();
            }
            
            // Create Course Invoice Entry...
            DB::table('course_invoice')->insert([
                'course_id' => $request->course,
                'invoice_id' => $invoice->id
            ]);

            $note = new Note([
                'type' => 'new_subscription',
                'description' => 'New Course Subscription started for '.$plan->name.' (' . ucfirst($plan->interval) . 'ly)',
                'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
            ]);
            $user->notes()->save($note);

            if (!$user->isCourseSubscribed($course)) {
                $user->courses()->save($course);
            }
            $subscription = $user->subscriptions()->where('name', 'course')->where('plan_id', $plan->id)->first();
            if(!$subscription) {
                $subscription =   $user->newSubscription('course', $plan)->create();
                $full_payment = 0 ;
                if($course->course_type == 'full'){
                    $full_payment = 1 ;
                }
                // $subscription->student_number = $studentNumber;
                $subscription->starts_at = $course->start_date;
                $subscription->ends_at = $course->end_date;
                $subscription->no_of_debit_order = $course->discounted_debit_order;
                $subscription->completed_semester = 0;
                $subscription->course_type = $course->type_of_course;
                $subscription->full_payment = $full_payment;
                $subscription->completed_order = 0;
                $subscription->invoice_id = $invoice->id;
                $subscription->save();
            }
            

            $this->sendInvoiceRepository->sendInvoice(User::find($request->user_id), $invoice->fresh());
            $invoiceRepository->changeInvoiceDateTo($invoice, Carbon::parse($request->date));

            alert()->success('Invoice Created Successfully', 'Success');
            return redirect()->back()->withInput(['tab' => 'panel_create_new_invoice']);
        }
        alert()->error('Please select valid course!', 'Error');
        return redirect()->back()->withInput(['tab' => 'panel_create_new_invoice']);
    }

    public function createNewCombinedInvoice(Request $request, InvoiceRepository $invoiceRepository)
    {
      
        $this->validate($request, ['option_id' => 'required', 'description' => 'required', 'price' => 'required','type'=>'required']);
        $invoice = $invoiceRepository->createCombinedInvoice(User::find($request->user_id), $request);
        if(!$request->include_vat){
            $invoice->vat_rate = 0;
            $invoice->save();
        }
        $this->sendInvoiceRepository->sendInvoice(User::find($request->user_id), $invoice->fresh());

        alert()->success('Invoice Created Successfully', 'Success');
        return redirect()->back();
    }

    public function postAllocate(Request $request)
    {
        $invoice = Invoice::with('transactions')->find($request->invoice_id);

        // Create Transaction
        $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice['id'],
            'type' => 'credit',
            'display_type' => 'Payment',
            'status' => 'Closed',
            'category' => $invoice['type'],
            'amount' => $request['amount'],
            'ref' => $invoice['reference'],
            'method' => $request['method'],
            'description' => $request['description'],
            'tags' => "Payment",
            'date' => Carbon::parse($request['date_of_payment'])->endOfDay(),
            'notes' => $request['notes']
        ]);

        $this->invoiceRepository->allocateNotePayment($request, $invoice->fresh());
        $this->invoiceRepository->trySettlement($invoice->fresh());
        if($invoice->fresh()->balance == 0)
        {
            $invoice->date_settled = Carbon::parse($request['date_of_payment'])->endOfDay();
            $invoice->save();
        }

        alert()->success('Payment Allocated Successfully', 'Success');
        return redirect()->back();
    }


    public function applyCreditNote(Request $request, $id)
    {
        $this->validate($request, [ 'amount' => 'required' ]);
        $invoice = Invoice::findorFail($id);

        $transaction = $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'credit',
            'display_type' => 'Credit Note',
            'status' => 'Closed',
            'category' => $invoice->type,
            'amount' => $request->amount,
            'ref' => $invoice->reference,
            'method' => 'Applied',
            'description' => "Invoice #{$invoice->reference} discount",
            'tags' => "Discount",
            'date' => Carbon::now()
        ]);

        $this->creditMemoRepository->store($transaction);
        $this->creditMemoRepository->creditNoteAllocate($request, $invoice);
        $this->invoiceRepository->trySettlement($invoice);

        alert()->success('Your credit note has been added', 'success!');
        return redirect()->back()->withInput(['tab' => 'user_invoices']);
    }

    public function consolidate($member)
    {
        $lineItems = collect();
        $user = User::with('invoices')->find($member);
        $invoices = $user->invoices()->where('type', 'subscription')->where('status', 'unpaid')->where('paid', false)->get();

        $invoices->each(function ($invoice) use($lineItems){
            $invoice->items->each(function ($item) use($lineItems){
                $item->update([
                    'description' => $item->description.' '.date_format($item->created_at, 'd F Y')
                ]);
                $lineItems->push($item);
            });
            $this->tryCancel($invoice);
        });

        $newInvoice = $this->createInvoice($user, $lineItems->sum('price'), 0, $lineItems->sum('price'), $lineItems->sum('price'), $lineItems->sum('price'));
        $newInvoice->addItems($lineItems);
    }

    public function createInvoice(User $user, $reference, $discount = 0, $sub_total, $total, $balance, $paid = false, $type = 'subscription')
    {
        if($paid == true) {
            $status = 'paid';
        } else {
            $status = 'unpaid';
        }

        return $user->invoices()->create([
            'reference' => $reference,
            'discount' => $discount,
            'vat_rate' => 14,
            'sub_total' => $sub_total,
            'total' => $total,
            'balance' => $balance,
            'paid' => $paid,
            'status' => $status,
            'type' => $type
        ]);
    }

    public function tryCancel($invoice)
    {
        // Create Credit note
        try{
            $transaction = $invoice->transactions()->create([
                'user_id' => $invoice->user->id,
                'invoice_id' => $invoice->id,
                'type' => 'credit',
                'display_type' => 'Credit Note',
                'status' => 'Closed',
                'category' => $invoice->type,
                'amount' => $invoice->balance,
                'ref' => $invoice->reference,
                'method' => 'Void',
                'description' => "Invoice #{$invoice->reference} cancellation",
                'tags' => "Cancellation",
                'date' => Carbon::now()
            ]);

            // Set Cancelled on Invoice
            $invoice->cancelled = 1;
            $invoice->status = 'credit noted';
            $invoice->save();

            // Create new entry for credit memo
            $this->creditMemoRepository->store($transaction);

            $note = new Note([
                'type' => 'general',
                'description' => "Invoice has been consolidated # ".$invoice->reference,
                'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
            ]);

            $invoice->user->notes()->save($note);
    }catch (Exception $exception) {
            return $exception;
        }
    }

    public function resend_invoice($id)
    {
        $invoice = Invoice::find($id);
        $this->sendInvoiceRepository->sendInvoice($invoice->user, $invoice);

        $note = new Note([
            'type' => 'email',
            'description' => 'I have re-send invoice'.' #'.$invoice->reference.' to the client',
            'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
        ]);
        $invoice->user->notes()->save($note);

        alert()->success('The invoice has been sent to the user successfully!', 'Invoice Sent!');
        return back();
    }

    public function send_statement($member)
    {
        $message = \Input::get('message');
        $user = User::find($member);

        $job = (new SendAccountStatementJOb($user, $message))->onQueue('default')->delay(50);
        $this->dispatch($job);

        $note = new Note([
            'type' => 'email',
            'description' => 'I have send the account statement to the client',
            'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
        ]);

        $user->notes()->save($note);
        alert()->success('The statement has been sent to the user successfully!', 'Statement Sent!');
        return back();
    }
}
