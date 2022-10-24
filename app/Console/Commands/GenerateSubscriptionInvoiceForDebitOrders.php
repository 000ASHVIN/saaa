<?php

namespace App\Console\Commands;

use App\Billing\InvoiceRepository;
use App\Jobs\NotifyClientOfInvalidDebitOrderDetails;
use App\Note;
use App\Repositories\CreditMemo\CreditMemoRepository;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Mail;

class GenerateSubscriptionInvoiceForDebitOrders extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debit-order:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate subscription invoices for clients who are on debit order..';
    private $invoiceRepository;
    private $sendInvoiceRepository;
    private $creditMemoRepository;

    /**
     * Create a new command instance.
     */
    public function __construct(InvoiceRepository $invoiceRepository, SendInvoiceRepository $sendInvoiceRepository, CreditMemoRepository $creditMemoRepository)
    {
        parent::__construct();
        $this->invoiceRepository = $invoiceRepository;
        $this->sendInvoiceRepository = $sendInvoiceRepository;
        $this->creditMemoRepository = $creditMemoRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscriptions = Subscription::with('user', 'plan')
            ->active()
            ->renewable()
            ->get();

        $debitOrderSubscriptions = $subscriptions->filter(function ($subscription){
           if($subscription->user->payment_method == 'debit_order'){
               return $subscription;
           }
        });

        foreach ($debitOrderSubscriptions as $debitOrderSubscription){
            if ($debitOrderSubscription->user->debit){

                // Check if the user debit order billable date is tomorrow, if Yes, we need to generate a new invoice..
                $tomorrow = Carbon::now()->addDay(1)->day;
                $current_debit_order_date = (int)$debitOrderSubscription->user->debit->billable_date;

                if ($current_debit_order_date === $tomorrow){

                    // Generate invoice for the user..
                    $this->info('We are crafting a new invoice for '.$debitOrderSubscription->user->email);

                    // Generate a new invoice
                    $invoice = $this->generateInvoice($debitOrderSubscription);

                    // Set the invoice_id to the subscriptions table so that we know which invoice it is if the payment failed.
                    $debitOrderSubscription->setInvoiceId($invoice);
                }
            }else{
                $this->info('We are crafting a new invoice for '.$debitOrderSubscription->user->email);

                // Generate a new invoice
                $invoice = $this->generateInvoice($debitOrderSubscription);

                // Email User Regarding Failed Debit Order
                $this->dispatch((new NotifyClientOfInvalidDebitOrderDetails($debitOrderSubscription->user->debit)));

                // Set the invoice_id to the subscriptions table so that we know which invoice it is if the payment failed.
                $debitOrderSubscription->setInvoiceId($invoice);
            }
        }
    }

    public function generateInvoice($subscription)
    {
        // 1. Generate Invoice
        $invoice = $this->invoiceRepository->createSubscriptionInvoice($subscription->user, $subscription->plan);
        $invoice->save();

        if (! $subscription->billable()){
            $this->creditMemoRepository->cancelInvoiceAndCreditNote($invoice, $description = 'Subscription Invoice cancelled due to free membership');
        }

        if ($subscription->user->subscription('cpd')->agent_id != null){

            DB::transaction(function () use($subscription, $invoice){
                $agent = User::find($subscription->user->subscription('cpd')->agent_id);
                $note = new Note([
                    'type' => 'recurring_subscription',
                    'description' => 'Recurring Invoice for CPD subscription '.$subscription->user->subscription('cpd')->plan->name,
                    'logged_by' => $agent->first_name .' '.$agent->last_name,
                ]);

                $note->invoice()->associate($invoice);
                $subscription->user->notes()->save($note);
            });
        }

        try{
            $this->sendInvoiceRepository->sendInvoice($subscription->user, $invoice->fresh());
        }catch (Exception $exception){
            return $exception;
        }

        return $invoice;
    }
}
