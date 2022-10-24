<?php

namespace App\Listeners;

use App\DebitOrder;
use App\Jobs\NotifyClientOfInvalidDebitOrderDetails;
use App\Jobs\SendRenewableInvoice;
use App\Jobs\SendRenewableNotificationToUserWithCreditCard;
use App\Note;
use App\Peach;
use App\Billing\InvoiceRepository;
use App\Events\CourseSubscriptionRenewed;
use App\Repositories\CreditMemo\CreditMemoRepository;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Repositories\WalletRepository\WalletRepository;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use WebDriver\Exception;
use App\Billing\Invoice;

class GenerateCourseSubscriptionInvoice
{
    use DispatchesJobs;
    private $invoiceRepository;
    private $peach;
    private $walletRepository;
    private $sendInvoiceRepository;
    /**
     * @var CreditMemoRepository
     */
    private $creditMemoRepository;

    /**
     * Create the event listener.
     *
     * @param InvoiceRepository $invoiceRepository
     * @param Peach $peach
     * @param WalletRepository $walletRepository
     * @param SendInvoiceRepository $sendInvoiceRepository
     * @param CreditMemoRepository $creditMemoRepository
     */
    public function __construct(InvoiceRepository $invoiceRepository, Peach $peach, WalletRepository $walletRepository, SendInvoiceRepository $sendInvoiceRepository, CreditMemoRepository $creditMemoRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->peach = $peach;
        $this->walletRepository = $walletRepository;
        $this->sendInvoiceRepository = $sendInvoiceRepository;
        $this->creditMemoRepository = $creditMemoRepository;
    }

    /**
     * Handle the event.
     *
     * @param  CourseSubscriptionRenewed $event
     * @return \Exception|Exception
     */
    public function handle(CourseSubscriptionRenewed $event)
    {
        $subscription = $event->subscription;
        // 1. Generate Invoice
        if($event->plan->price > 0){
            if($event->subscription->completed_order < $event->subscription->no_of_debit_order && $event->user->getCourseInvoiceByPlanId($event->subscription->plan_id)){
                $invoice = $this->invoiceRepository->createCourseSubscriptionInvoice($event->user, $event->plan,$event->subscription);
                $this->addDebitTransaction($invoice);
                $invoice->save();
                $course = $event->plan->getCourseByPlanId();
                if($course && $course->exclude_vat == 1){
                    $invoice->vat_rate = 0;
                    $invoice->save();
                }
                if (! $subscription->billable()){
                    $this->creditMemoRepository->cancelInvoiceAndCreditNote($invoice, $description = 'Subscription Invoice cancelled due to free membership');
                }
            }
            $subscription->setInvoiceId($invoice);
        }

       

        // * Add note to user Profile for recurring Comm if agent ID was set.
        if ($subscription->agent_id != null){
            try{
                $agent = User::find($subscription->agent_id);
                $note = new Note([
                    'type' => 'recurring_subscription',
                    'description' => 'Recurring Invoice for CPD subscription '.$subscription->plan->name,
                    'logged_by' => $agent->first_name .' '.$agent->last_name,
                ]);
                if($event->plan->price > 0 && isset($invoice)){
                    $note->invoice()->associate($invoice);
                }
                $event->user->notes()->save($note);

            }catch (\Exception $exception){
                return $exception;
            }
        }

//        // 2 Check the user funds available in the wallet.
//        try{
//            if ($event->user->availableWallet != null){
//                $this->walletRepository->payInvoice($event->user->id, $invoice->id);
//            }
//        }catch (Exception $exception){
//            return $exception;
//        }

    if($event->plan->price > 0 && isset($invoice)){
        if($event->subscription->name == 'course'){
            if($event->subscription->completed_order < $event->subscription->no_of_debit_order && $event->user->getCourseInvoiceByPlanId($event->subscription->plan_id)){
                    // 3. Check if Subcsription payment method is CC
                    switch ($event->user->payment_method) {
                        case 'credit_card':
                            $this->processCard($event->user, $invoice->fresh(),$subscription);
                            break;
                        case 'debit_order':
                            $this->processDebitOrder($event->user, $invoice->fresh());
                            break;
                    }
            }
        }

        try{
            $this->dispatch((new SendRenewableInvoice($event->user, $invoice->fresh())));
        }catch (Exception $exception){
            return $exception;
        }
        }
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

    public function processCard($user, $invoice,$subscription)
    {
        // 3.1 If user has card on file, attempt to charge card
        if($user->primaryCard) {
            if ($subscription->plan->interval != 'year'){
                $payment = $this->peach->charge(
                    $user->primaryCard->token,
                    $invoice->balance,
                    'CPD Renewal #'.$invoice->reference . " - " . time(),
                    $invoice->reference
                );

                if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {
                    $invoice->settle();
                    $subscription->completed_order = $subscription->completed_order+ 1;
                    $subscription->save();
                    $this->allocatePayment($invoice, $invoice->balance, "#{$invoice->reference} Payment", $method = 'cc');
                    $this->sendEmail($user, 'emails.subscription.renewal_successful', 'Your payment was successfull');
                } else {
                    $this->sendEmail($user, 'emails.subscription.renewal_unsuccessful', 'Your payment was unsuccessfull');
                }
            }else{
                // Save a note on the user account to indicate that Peach Payments did not charge his credit card.
                $note = new Note([
                    'type' => 'general',
                    'description' => 'Invoice for yearly subscription was not charged through Peach Payments',
                    'logged_by' => 'system',
                ]);

                $note->invoice()->associate($invoice);
                $user->notes()->save($note);
            }

        } else {
            $this->sendEmail($user, 'emails.subscription.renewal_no_card', 'Your payment was unsuccessfull');
        }
    }

    public function allocatePayment($invoice, $amount, $description, $method)
    {
        $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'credit',
            'display_type' => 'Payment',
            'status' => 'Closed',
            'category' => $invoice->type,
            'amount' => $amount,
            'ref' => $invoice->reference,
            'method' => $method,
            'description' => $description,
            'tags' => "Payment",
            'date' => Carbon::now()
        ]);
    }

    public function processDebitOrder($user, $invoice)
    {
        if ($user->debit == null){
        $debit_order = $this->createDebitOrder($user);
        $this->dispatch((new NotifyClientOfInvalidDebitOrderDetails($debit_order)));
    }
    }

    public function sendEmail($user, $view, $subject)
    {
        $this->dispatch((new SendRenewableNotificationToUserWithCreditCard($user, $view, $subject)));
    }

    /**
     * @param $user
     * @return DebitOrder
     */
    private function createDebitOrder($user)
    {
        $debit_order = new DebitOrder([
            'bank' => '',
            'number' => '',
            'type' => '',
            'branch_name' => '',
            'branch_code' => '',
            'billable_date' => '',
            'id_number' => $user->id_number,
            'registration_number' => '',
            'account_holder' => ucfirst($user->first_name) . ' ' . ucfirst($user->last_name),
            'type_of_account' => '',
        ]);
        $debit_order->user()->associate($user);
        $debit_order->save();
        return $debit_order;
    }
}
