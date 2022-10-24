<?php

namespace App\Billing;

use App\Note;
use App\Repositories\CreditMemo\CreditMemoRepository;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Services\Invoicing\SendPdfInvoice;
use Carbon\Carbon;
use App\Users\User;
use App\Subscriptions\Models\Plan;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Donation;
use App\Repositories\Donation\DonationRepository;
use App\Subscriptions\Models\Subscription;

class InvoiceRepository
{

    private $sendInvoiceRepository;
    private $creditMemoRepository;
    private $donationRepository;
    public function __construct(SendInvoiceRepository $sendInvoiceRepository, CreditMemoRepository $creditMemoRepository, DonationRepository $donationRepository)
    {
        $this->sendInvoiceRepository = $sendInvoiceRepository;
        $this->creditMemoRepository = $creditMemoRepository;
        $this->donationRepository = $donationRepository;
    }

    /**
     * @param User $user
     * @param $plan
     * @return Invoice
     */
    public function createSubscriptionInvoice(User $user, $plan)
    {
        $invoice = $this->createInvoice($user, $plan->name, 0, $plan->price, $plan->price, $plan->price);
        $this->addSubscriptionLineItem($invoice, 'subscription', $plan->name, ucfirst(strtolower(strip_tags($plan->invoice_description))), $plan->price,$plan);
        return $invoice;
    }

    /**
     * @param User $user
     * @param $plan
     * @return Invoice
     */
    public function createSubscriptionInvoiceCustom(User $user, $plan,$qty)
    {
        $plan->price = $plan->price* $qty;
        $invoice = $this->createInvoice($user, $plan->name, 0, $plan->price, $plan->price, $plan->price);
        $this->addSubscriptionLineItem($invoice, 'subscription', $plan->name, ucfirst(strtolower(strip_tags($plan->invoice_description))), $plan->price,$plan,$qty);
        return $invoice;
    }
    /**
     * @param User $user
     * @param $plan
     * @return Invoice
     */
    public function createChildSubscriptionInvoice(User $user, $subscription)
    {  
        $plan = $subscription->plan;
        $activeStaff = 1;
        if($user->isPracticePlan()){
            $activeStaff = $user->isPracticePlan();
            $subscription->plan->getPlanPrice($activeStaff) ;
            $pricingGroup = @$subscription->getChildSubscriptions[0];
            $plan->price=($subscription->plan->price)*$activeStaff;
            
            if($pricingGroup && $pricingGroup->pricings){
                $plan->price=($pricingGroup->pricings->price)*$activeStaff;
                
                $request = request();
                $request->merge(['staff'=>$activeStaff]);
            }
            
        }
        if($user->additional_users > 0 || $user->additional_users < 0 )
        {
            $user->additional_users = 0;
            $user->save();
        }
        $invoice = $this->createInvoice($user, $plan->name, 0, $plan->price, $plan->price, $plan->price);
        $this->addSubscriptionLineItem($invoice, 'subscription', $plan->name, ucfirst(strtolower(strip_tags($plan->invoice_description))), $plan->price,$plan,$activeStaff,$plan->id);
        return $invoice;
    }

    public function createCourseInvoice(User $user, $course, $request)
    {
        $price = 0;
        $discount  = 0;
        if ($request->enrollment_option === 'yearly') {
            $price = $course->yearly_enrollment_fee;
            $discount = $course->annual_discount;
            if($course->type_of_course == 'semester') {
                if($course->course_type == 'partially') {
                    $price = $course->semester_price;
                }elseif($course->course_type == 'full') {
                    $price = ($course->semester_price)*($course->no_of_semesters);
                }
            }
        
        }else{
            $price = $course->monthly_enrollment_fee;
        }

        $description = $request->description != '' ? $request->description : 'Online Course Access';
        $invoice = $this->createInvoice($user, $course->title, $discount, $price, $price, $price, false, 'course');
        $this->addCourseLineItem($invoice, 'product', $course->title, $description, $price, $course);
        $this->addDebitTransaction($invoice);
        return $invoice;
    }

    public function addDebitTransaction($invoice)
    {
        $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'debit',
            'display_type' => 'Invoice',
            'status' => 'Open',
            'category' => 'course',
            'amount' => $invoice->total,
            'ref' => $invoice->reference,
            'method' => 'Void',
            'description' => 'Invoice '.$invoice->reference,
            'tags' => "Invoice",
            'date' => Carbon::now()->addSeconds(5)
        ]);
    }
    
       /**
     * @param User $user
     * @param $plan
     * @return Invoice
     */
    public function createCourseSubscriptionInvoice(User $user, $plan,$subscription)
    {
        $invoice = $this->createInvoice($user, $plan->name, 0, $plan->price, $plan->price, $plan->price,false,'course');
        $this->addLineItem($invoice, 'course', $plan->name, ucfirst(strtolower(strip_tags($plan->invoice_description))), $plan->price,$plan);
        return $invoice;
    }

    public function createCombinedInvoice(User $user, $combined)
    {
        $option = Item::find($combined->option_id);

        $invoice = $this->createInvoice($user, $option->name, 0, $combined->price, $combined->price, $combined->price);
        $invoice->update(['type' => $combined->type]);
        $invoice->save();

        $item = Item::create([
            'type' => $combined->type,
            'name' => $option->name,
            'description' => ucfirst(strtolower(strip_tags($combined->description))),
            'price' => $combined->price,
            'item_id' => $combined->id ?? 0,
            'item_type' => get_class($combined),
        ]);

        $invoice->fresh()->addItem($item);
        return $invoice;
    }

    /**
     * @param User $user
     * @param $reference
     * @param int $discount
     * @param $sub_total
     * @param $total
     * @param $balance
     * @param bool $paid
     * @param string $type
     * @return Invoice
     */
    public function createInvoice(User $user, $reference, $discount = 0, $sub_total, $total, $balance, $paid = false, $type = 'subscription')
    {
        if($paid == true) {
            $status = 'paid';
        } else {
            $status = 'unpaid';
        }

        /*
        * Check if donations exists
        */
        $donation = 0;
        $donation_id = 0;
        if($type == 'subscription' && function_exists('request')) {
            
            $donation = request()->donations?request()->donations:0;
            if($donation>0) {
                $donate = $this->donationRepository->createForUserAndNotify($user, $donation);

                $donation_id = $donate->id;
                
                $sub_total = $sub_total + $donation;
                $total = $total + $donation;

            }
        }

        return $user->invoices()->create([
            'reference' => $reference,
            'discount' => $discount,
            'vat_rate' => 15,
            'sub_total' => $sub_total,
            'total' => $total,
            'balance' => $balance,
            'paid' => $paid,
            'status' => $status,
            'type' => $type,
            'donation' => $donation,
            'donation_id' => $donation_id
        ]);
    }

    /**
     * Change the date for any given invoice
     * @param Invoice $invoice
     * @param Carbon $date
     * @return bool
     */
    public function changeInvoiceDateTo(Invoice $invoice, Carbon $date)
    {
        $invoice->created_at = $date;
        return $invoice->save();
    }

    /**
     * @param Invoice $invoice
     * @param $type
     * @param $name
     * @param $description
     * @param $price
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function addLineItem(Invoice $invoice, $type, $name, $description, $price,$plan)
    {
        return $invoice->items()->create([
            'type' => $type,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'item_id' => $plan->id,
            'item_type' => get_class($plan),
        ]);
    }

private function addCourseLineItem(Invoice $invoice, $type, $name, $description, $price,$course,$qty = 1,$item_id = 0)
    {
        
        if ($qty > 1) {
            $price = $price / $qty;
        }
       
        return $invoice->items()->create([
            'type' => $type,
            'name' => $name,
            'description' => ucfirst(strtolower(strip_tags($description))),
            'price' => $price,
            'quantity'=>$qty,
            'item_id'=>$course->id,
            'item_type' => get_class($course),
        ]);
    }

    /**
     * @param Invoice $invoice
     * @param $type
     * @param $name
     * @param $description
     * @param $price
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function addSubscriptionLineItem(Invoice $invoice, $type, $name, $description, $price,$plan,$qty = 1,$item_id = 0)
    {
        
        if ($qty > 1) {
            $price = $price / $qty;
        }
       
        return $invoice->items()->create([
            'type' => $type,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'quantity'=>$qty,
            'item_id'=>$plan->id,
            'item_type' => get_class($plan),
        ]);
    }
    

    public function allocateNotePayment($request, $invoice)
    {
        $note = new Note([
            'type' => 'payment_allocate',
            'description' => 'I have allocated R' . number_format($request['amount'], 2) . ' to Invoice #' . $invoice['reference'] . ' for ' . Carbon::parse($request['date_of_payment'])->endOfDay()->toFormattedDateString(),
            'logged_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        ]);
        $invoice->user->notes()->save($note);
    }

    public function removeNotePayment($request, $transaction)
    {
        $note = new Note([
            'type' => 'payment_delete',
            'description' => 'I have removed payment R' . number_format($transaction->amount, 2) . ' from Invoice #' . $transaction->invoice['reference'],
            'logged_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        ]);
        $transaction->invoice->user->notes()->save($note);
    }

    public function trySettlement($invoice)
    {
        if($invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount') <= 0 && $invoice->fresh()->status != 'cancelled' && $invoice->fresh()->cancelled != '1')
        {
            $invoice->settle();
        }
    }

    public function calculateTheDiscount($oldPlan, $newPlan, $invoice)
    {
        if ($oldPlan->interval == 'year' && $newPlan->interval == 'year'){
            $total = $oldPlan->price / 12;
            $difference = $invoice->user->subscription('cpd')->starts_at->startOfMonth()->startOfDay()->diffinMonths(Carbon::now());

            $deduction = $total * $difference;
            $remaining = $oldPlan->price - $deduction;

            if ($remaining > 0){
                $transaction = $invoice->transactions()->create([
                    'user_id' => $invoice->user->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'credit',
                    'display_type' => 'Credit Note',
                    'status' => 'Closed',
                    'category' => $invoice->type,
                    'amount' => round($remaining),
                    'ref' => $invoice->reference,
                    'method' => 'Applied',
                    'description' => "Invoice #{$invoice->reference} discount",
                    'tags' => "Discount",
                    'date' => $invoice->created_at->addSeconds(10)
                ]);

                $this->creditMemoRepository->store($transaction);

                $Credit_note = new Note([
                    'type' => 'credit_note',
                    'description' => 'Created a Credit note for the amount of R'.number_format(round($remaining), 2). ' Client upgraded and was only active or '.$difference .' months',
                    'logged_by' => 'System',
                ]);
                $invoice->user->notes()->save($Credit_note);
            }
        }
    }

    public function addDebitTransactionToInvoice($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        if (! $invoice->transactions->contains('do_failed', true)){
            DB::transaction(function () use($invoice){
                $invoice->transactions()->create([
                    'user_id' => $invoice->user->id,
                    'invoice_id' => $invoice->id,
                    'type' => 'debit',
                    'display_type' => 'Adjustment',
                    'status' => 'Open',
                    'category' => $invoice->type,
                    'amount' => $invoice->transactions()->where('type', 'debit')->first()->amount,
                    'ref' => $invoice->reference,
                    'method' => 'Void',
                    'description' => "#{$invoice->reference} not provided for",
                    'tags' => "Adjustment",
                    'date' => Carbon::now(),
                    'do_failed' => true
                ]);

                $invoice->paid = false;
                $invoice->status = 'unpaid';
                $invoice->date_settled = '';
                $invoice->balance = ($invoice->transactions()->whereType('debit')->sum('amount') - $invoice->transactions()->whereType('credit')->sum('amount')) / 100;
                $invoice->save();
            });
        }
    }
}