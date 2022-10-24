<?php

namespace App\Console\Commands;

use App\Billing\Invoice;
use App\DebitOrder;
use App\Debit;
use App\InvalidDebitOrderDetail;
use App\Jobs\NotifyClientOfInvalidDebitOrderDetails;
use App\Jobs\NotifyITDebitOrderpaymentAllocationFailed;
use App\Note;
use App\PeachDo;
use App\Subscriptions\Models\Period;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use test\Mockery\SubclassWithFinalWakeup;

class ProcessDebitOrders extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:debit-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Daily Debit Orders';


    protected $debits;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->debits = collect();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::now()->day;

        // Regular Debit Orders
        $processToday = DebitOrder::where('active', true)
            ->where('peach', true)
            ->where('bill_at_next_available_date', true)
            ->Orwhere('billable_date', $today)
            ->has('user')
            ->get();

        $urgent = $processToday->where('bill_at_next_available_date', 1)->where('peach', 1);
        $regular = $processToday->where('bill_at_next_available_date', 0)->where('peach', 1);

        if(! count($regular)) {
            $this->warn("No Regular Debit Orders To Process for Today");
        } else {
            $this->processDebitOrders($regular);
        }

        if(! count($urgent)) {
            $this->warn("No Urgent Debit Orders To Process for Today");
        } else {
            $this->processUrgentDebitOrders($urgent);
        }
    }

    private function processUrgentDebitOrders($debit_orders)
    {
        foreach($debit_orders as $debit_order) {
            $date = $this->updateDebitAndSubscriptionDate($debit_order);
            $debit_order->update(['bill_at_next_available_date' => false]);
            $debit_order->update(['billable_date' => $date]);

            $debit_order->last_debit = Carbon::now();
            $debit_order->save();
        }

        $debitOrders = [];
        foreach ($debit_orders as $debit_order) {
            $wasDebitted = Debit::where('user_id', $debit_order->user->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->where('type','subscription')->get();
            if (count($wasDebitted) == 0){
                if ($debit_order->skip_next_debit == true){
                    $this->info('Skipping debit order..');
                    $debit_order->update(['skip_next_debit' => false]);
                    $debit_order->save();
                }else{
                    if ($debit_order->user->payment_method == 'debit_order' && $debit_order->user->subscription('cpd')->plan->interval != 'year'){
                        $this->addDebitsEntry($debit_order);
                        array_push($debitOrders, $debit_order->toCustomArray());
                        continue;
                    }else{
                        $note = new Note([
                            'type' => 'general',
                            'description' => 'Unable to debit account due to payment method not set to Debit Order',
                            'logged_by' => 'system',
                        ]);
                        $debit_order->user->notes()->save($note);
                        continue;
                    }
                }
            }
        }
        $this->loadDebitOrders($debitOrders);
    }

    private function processDebitOrders($debit_orders)
    {
        foreach ($debit_orders as $debit_order){
            if($debit_order->user->status == 'active'){
                $date = $this->updateDebitAndSubscriptionDate($debit_order);
                $debit_order->update(['billable_date' => $date]);

                $debit_order->last_debit = Carbon::now();
                $debit_order->save();
            }
        }

        $debitOrders = [];
        foreach ($debit_orders as $debit_order) {
            if($debit_order->user->status == 'active'){
            $wasDebitted = Debit::where('user_id', $debit_order->user->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->where('type','subscription')->get();
            $InvoiceGenerated = $debit_order->user->invoices()->where('type','subscription')->where('created_at','>',Carbon::now()->startOfMonth())->first();
            if (count($wasDebitted) == 0){
                if ($debit_order->skip_next_debit == true){
                    $this->info('Skipping debit order..');
                    $debit_order->update(['skip_next_debit' => false]);
                    $debit_order->save();
                }else{

                    if($InvoiceGenerated){
                        if ($debit_order->user->payment_method == 'debit_order' && $debit_order->user->subscription('cpd')->plan->interval != 'year'){
                            $this->addDebitsEntry($debit_order);
                            array_push($debitOrders, $debit_order->toCustomArray());
                            continue;
                        } else {
                            $note = new Note([
                                'type' => 'general',
                                'description' => 'Unable to debit account due to payment method not set to Debit Order',
                                'logged_by' => 'system',
                            ]);
                            $debit_order->user->notes()->save($note);
                            continue;
                        }
                    }
                }
            }
        }
        }

        $this->loadDebitOrders($debitOrders);
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
    private function addDebitsEntry($debit_order)
    {
        $debit = Debit::create([
            'number' => $debit_order->number,
            'branch_code' => $debit_order->branch_code,
            'amount' => (int) $debit_order->toCustomArray()['amount'],
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
