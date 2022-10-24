<?php

namespace App\Console\Commands;

use App\Debit;
use App\PeachDo;
use Carbon\Carbon;
use App\DebitOrder;
use App\Users\User;
use GuzzleHttp\Client;
use App\Billing\Invoice;
use Illuminate\Console\Command;
use App\InvalidDebitOrderDetail;
use App\Billing\InvoiceRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\NotifyClientOfInvalidDebitOrderDetails;

class YearlyPracticePlan extends Command
{
    use DispatchesJobs;
    protected $invoiceRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'year:practice:plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All yearly practice plan renew';

    protected $debits;
    /**
     * Create a new command instance.
     *
     */
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        parent::__construct();
        $this->debits = collect();
        $this->invoiceRepository= $invoiceRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       try {
        $debitOrders = [];   
        $users = User::has('company')->get();
        foreach($users as $user){//  && $user->subscription('cpd')->plan->interval == 'year'
            if($user->additional_users>0 && $user->subscription('cpd') && $user->subscription('cpd')->plan && $user->subscription('cpd')->plan->is_practice && $user->subscription('cpd')->plan->interval == 'year'){
                $plan = $user->subscription('cpd')->plan;
                $staff = $user->isPracticePlan()+$user->additional_users;
                $plan->getPlanPrice($staff); 
                $invoice = $this->invoiceRepository->createSubscriptionInvoiceCustom($user,$plan ,$user->additional_users);
                $additional_users = $user->additional_users;
                $user->update([
                    'additional_users' => 0
                ]);
                if($user->payment_method == 'debit_order')
                {
                    if($user->debit){
                            $debitCustom = $user->debit->toCustomArray();
                            $debitCustom['amount']=(float) $user->subscription('cpd')->plan->price * $additional_users;
                    
                            $this->addDebitsEntry($user->debit,$debitCustom['amount']);
                            array_push($debitOrders, $debitCustom);
                            continue;                        
                    }
                }
                 
            }
        }
        // $this->loadDebitOrders($debitOrders);
       } catch (\Exception $e) {
          
       }
    }

    private function loadDebitOrders($data) {
        $do = new PeachDo(new Client);
        $response = $do->load(Carbon::now()->format('Ymd'), $data);

        $this->setBatchCode($response);
        $this->processResponses($response);
    }

    private function setBatchCode($response) {
        foreach($this->debits as $debit) {
            $debit->update([
                'batch_id' => $response["BatchCode"]
            ]);
        }
    }
    private function processResponses($responses)
    {
        if (isset($responses['CDVResults']['Result']['Reference'])){
            if ($responses['CDVResults']['Result']['Result'] != 'Valid'){
                $this->processInvalidResponse($responses);
            }else{
                $this->processValidResponse($responses);
            }
        }else{
            foreach ($responses['CDVResults']['Result'] as $result) {
                if ($result["Result"] != "Valid"){

                    $account = (int) substr($result["CustomerCode"], 5);
                    $debit_order = DebitOrder::find($account);

                    $debit_order->update(['active' => 0, 'bill_at_next_available_date' => true]);
                    $debit = Debit::where('batch_id', $responses['BatchCode'])->where('number', $debit_order->number)->first();
                    $debit->update(['status' => 'unpaid', 'reason' => 'Invalid Account Details']);

                    // Email User Regarding Failed Debit Order
                    $this->dispatch((new NotifyClientOfInvalidDebitOrderDetails($debit_order)));

                    // Log Entry in invalid debit order details.
                    $this->logInvalidEntry($result, $debit_order);
                    continue;

                }else{
                    $account = (int) substr($result["CustomerCode"], 5);
                    $debit_order = DebitOrder::find($account);
                    $debit_order->last_debit = Carbon::now();

                    if ($debit_order->user->subscription('cpd')->plan->interval == 'month'){
                        $debit_order->next_debit_date = Carbon::now()->addMonth()->day($debit_order->billable_date)->startOfDay();
                    }else{
                        $debit_order->next_debit_date = Carbon::now()->addMonth()->day($debit_order->billable_date)->startOfDay()->addYear(1);
                    }

                    $debit_order->save();
                    $this->TryAllocatePayment($debit_order);
                    continue;
                }
            }
        }
    }

    
    /**
     * @param $result
     * @param $debit_order
     */
    private function logInvalidEntry($result, $debit_order)
    {
        InvalidDebitOrderDetail::create([
            'result' => $result["Result"],
            'message' => $result["Message"],
            'user_id' => $debit_order->user->id,
            'reference' => $result["Reference"],
            'customer_code' => $result["CustomerCode"],
            'account_number' => $result["AccountNumber"],
            'branch_code' => $result["BranchCode"],
        ]);
    }
    /**
     * @param $debit_order
     * @return mixed
     */
    private function updateDebitAndSubscriptionDate($debit_order)
    {
        $date = $debit_order->getSubscriptionAndBillableDate();
        $debit_order->user->subscription('cpd')->starts_at = $debit_order->user->subscription('cpd')->starts_at;

        if($debit_order->user->subscription('cpd')->plan->interval == 'month'){
            $debit_order->user->subscription('cpd')->ends_at = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($debit_order->user->subscription('cpd')->starts_at), 'F') . ' ' . Carbon::now()->year)->addMonth(1);
        }else{
            $debit_order->user->subscription('cpd')->ends_at = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($debit_order->user->subscription('cpd')->starts_at), 'F') . ' ' . Carbon::now()->year)->addMonth(1)->addYear(1);
        }
        $debit_order->user->subscription('cpd')->save();
        return $date;
    }
    /**
     * @param $debit_order
     * @throws \Exception
     */
    private function TryAllocatePayment($debit_order)
    {
        if ($debit_order->user->subscription('cpd')->invoice_id){
            $invoice = Invoice::where('id', $debit_order->user->subscription('cpd')->fresh()->invoice_id)->first();
            $invoice->settle();

            $this->allocatePayment($invoice, $invoice->transactions()->where('type', 'debit')->first()->amount, "#{$invoice->reference} Debit Order Payment", $method = 'debit');
            $failedTransaction = $invoice->transactions()->where('do_failed', true)->first();

            if ($failedTransaction){
                $failedTransaction->update(['do_failed' => false]);
            }
        }else{
            $invoice = $debit_order->user->invoices->where('type', 'subscription')->where('status', 'unpaid')->first();
            if ($invoice){
                $invoice->settle();
                $this->allocatePayment($invoice, $invoice->transactions()->where('type', 'debit')->first()->amount, "#{$invoice->reference} Debit Order Payment", $method = 'debit');
                $failedTransaction = $invoice->transactions()->where('do_failed', true)->first();

                if ($failedTransaction){
                    $failedTransaction->update(['do_failed' => false]);
                }

            }else{
                $debit_order->user->wallet->add($debit_order->user->subscription('cpd')->plan->price, 'debit', Carbon::now(), null, null);
            }
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

    /**
     * @param $debit_order
     */
    private function addDebitsEntry($debit_order,$amount)
    {
        $debit = Debit::create([
            'number' => $debit_order->number,
            'branch_code' => $debit_order->branch_code,
            'amount' => (int) $amount,
            'user_id' => $debit_order->user_id,
            'status' => 'paid',
            'type'=>'subscription',
            'type_id'=> (int) $debit_order->user->subscription('cpd')->plan->id
        ]);

        $this->debits->push($debit);
    }

    /**
     * @param $responses
     */
    private function processInvalidResponse($responses)
    {
        $account = (int)substr($responses['CDVResults']['Result']["CustomerCode"], 5);
        $debit_order = DebitOrder::find($account);
        $debit_order->update(['active' => 0, 'bill_at_next_available_date' => true]);
        $debit = Debit::where('batch_id', $responses['BatchCode'])->where('number', $debit_order->number)->first();
        $debit->update(['status' => 'unpaid', 'reason' => 'Invalid Account Details']);

        // Email User Regarding Failed Debit Order
        $this->dispatch((new NotifyClientOfInvalidDebitOrderDetails($debit_order)));

        // Log Entry in invalid debit order details.
        $this->logInvalidEntry($responses['CDVResults']['Result'], $debit_order);
    }

    /**
     * @param $responses
     * @throws \Exception
     */
    private function processValidResponse($responses)
    {
        $account = (int)substr($responses['CDVResults']['Result']["CustomerCode"], 5);
        $debit_order = DebitOrder::find($account);
        $debit_order->last_debit = Carbon::now();

        if ($debit_order->user->subscription('cpd')->plan->interval == 'month') {
            $debit_order->next_debit_date = Carbon::now()->addMonth()->day($debit_order->billable_date)->startOfDay();
        } else {
            $debit_order->next_debit_date = Carbon::now()->addMonth()->day($debit_order->billable_date)->startOfDay()->addYear(1);
        }

        $debit_order->save();
        $this->TryAllocatePayment($debit_order);
    }
}
