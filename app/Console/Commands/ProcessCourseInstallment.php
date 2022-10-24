<?php

namespace App\Console\Commands;

use DB;
use App\Note;
use App\Debit;
use App\PeachDo;
use Carbon\Carbon;
use App\DebitOrder;
use App\Models\Course;
use GuzzleHttp\Client;
use App\Billing\Invoice;
use Illuminate\Console\Command;
use App\InvalidDebitOrderDetail;
use App\Billing\InvoiceRepository;
use App\Subscriptions\Models\Period;
use test\Mockery\SubclassWithFinalWakeup;
use App\Subscriptions\Models\Subscription;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\NotifyClientOfInvalidDebitOrderDetails;
use App\Jobs\NotifyITDebitOrderpaymentAllocationFailed;

class ProcessCourseInstallment extends Command
{
    use DispatchesJobs;
    private $invoiceRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:course:installment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Course Installment';


    protected $debits;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->debits = collect();
        $this->invoiceRepository = $invoiceRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $debitOrders = [];
        $Courses = Course::where('is_publish','1')->get();
        $users = array();
      
        
        
        foreach($Courses as $course){
            foreach($course->users->unique() as $user)
            {
               //if($user->status == 'active')
               { 
               
                    $subscription = $user->subscriptions->where('name','course')->where('plan_id',$course->monthly_plan_id)->first();
                    
                    if($subscription){
                        $subscription->fresh()->renew();
                        if($user->payment_method == 'debit_order' || $user->payment_method == 'credit_card')
                        {
                        $wasDebitted = Debit::where('user_id', $user->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->where('type','course')->where('type_id',$course->monthly_plan_id)->get();
                        if(count($wasDebitted) == 0)
                        {
                                $courseStartDate = Carbon::parse($course->start_date)->format('d');
                                $billableDate = $user->debit->billable_date;
                                //if($billableDate >= $courseStartDate)
                                {
                                    if($subscription->plan->price > 0){
                                        
                                        if($subscription->completed_order < $subscription->no_of_debit_order && $user->getCourseInvoiceByPlanId($course->monthly_plan_id)){
                                            if($user->payment_method == 'debit_order')
                                            {
                                                if($user->debit !=""){
                                                    $this->addDebitsEntry($user->debit,$subscription); 
                                                    $date = $this->updateDebitAndSubscriptionDate($user->debit);
                                                    $user->debit->update(['billable_date' => $date]);
                
                                                    $user->debit->last_debit = Carbon::now();
                                                    $user->debit->save();
                                                    array_push($debitOrders, $user->debit->toCustomArrayCourse($subscription));
                                                }
                                                //continue;        
                                            }
                                        }
                                    }
                                }
                                
                        }
                        }
                }
                }
            }
        }
        if(!empty($debitOrders)){
            $this->warn("Course Installment Started");
            $this->loadDebitOrders($debitOrders);
            
        }
        $this->warn("Course Installment Completed");
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
            
                if ($debit_order->skip_next_debit == true){
                    $this->info('Skipping debit order..');
                    $debit_order->update(['skip_next_debit' => false]);
                    $debit_order->save();
                }else{
                    $courses = $debit_order->user->subscriptions->where('name','course');

                    
                    foreach($courses as $course){
            
                        $wasDebitted = Debit::where('user_id', $debit_order->user->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->where('amount',($course->recurring_monthly_fee/100))->get();
                        if (count($wasDebitted) == 0){

                        if($course->completed_order < $course->no_of_debit_order){
                            if ($debit_order->user->payment_method == 'debit_order' && $course->plan->interval != 'year'){
                             $this->addDebitsEntry($debit_order,$course);
                            
                                array_push($debitOrders, $debit_order->toCustomArrayCourse($course));
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

    private function processDebitOrders($debit_orders)
    {
        foreach ($debit_orders as $debit_order){
            $date = $this->updateDebitAndSubscriptionDate($debit_order);
            $debit_order->update(['billable_date' => $date]);

            $debit_order->last_debit = Carbon::now();
            $debit_order->save();
        }

        $debitOrders = [];
        foreach ($debit_orders as $debit_order) {
            $wasDebitted = Debit::where('user_id', $debit_order->user->id)->where('created_at', '>=', Carbon::now()->startOfMonth())->get();
            if (count($wasDebitted) == 0){
                if ($debit_order->skip_next_debit == true){
                    $this->info('Skipping debit order..');
                    $debit_order->update(['skip_next_debit' => false]);
                    $debit_order->save();
                }else{
                    $courses = $debit_order->user->subscriptions->where('name','course');

                    
                    foreach($courses as $course){
                  
                        if($course->completed_order < $course->no_of_debit_order){
                            if ($debit_order->user->payment_method == 'debit_order' && $course->plan->interval != 'year'){
                             $this->addDebitsEntry($debit_order,$course);
                            
                                array_push($debitOrders, $debit_order->toCustomArrayCourse($course));
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
         $do = new PeachDo(new Client(['verify' => false ]));
         
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
                    $refrence = explode("-",$result["CustomerCode"]);
                    $account = (int) substr($refrence[0], 5);
                    $Subscriptionaccount = (int) substr($refrence[1], 11);

                    $subscription  = Subscription::find($Subscriptionaccount);
                    
                    $debit_order = DebitOrder::find($account);
                    $debit_order->last_debit = Carbon::now();

                    if ($subscription->interval == 'month'){
                        $debit_order->next_debit_date = Carbon::now()->addMonth()->day($debit_order->billable_date)->startOfDay();
                    }else{
                        $debit_order->next_debit_date = Carbon::now()->addMonth()->day($debit_order->billable_date)->startOfDay()->addYear(1);
                    }

                    $debit_order->save();
                    $this->TryAllocatePayment($subscription);
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
        /*$debit_order->user->subscription('cpd')->starts_at = $debit_order->user->subscription('cpd')->starts_at;

        if($debit_order->user->subscription('cpd')->plan->interval == 'month'){
            $debit_order->user->subscription('cpd')->ends_at = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($debit_order->user->subscription('cpd')->starts_at), 'F') . ' ' . Carbon::now()->year)->addMonth(1);
        }else{
            $debit_order->user->subscription('cpd')->ends_at = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($debit_order->user->subscription('cpd')->starts_at), 'F') . ' ' . Carbon::now()->year)->addMonth(1)->addYear(1);
        }
        $debit_order->user->subscription('cpd')->save();*/
        return $date;
    }
    /**
     * @param $debit_order
     * @throws \Exception
     */
    private function TryAllocatePayment($subscription)
    {
        if ($subscription->invoice_id){
            $invoice = Invoice::where('id', $subscription->invoice_id)->first();
            $invoice->settle();
            $subscription->completed_order = $subscription->completed_order+ 1;


            $this->allocatePayment($invoice, $invoice->transactions()->where('type', 'debit')->first()->amount, "#{$invoice->reference} Debit Order Payment", $method = 'debit');
            $failedTransaction = $invoice->transactions()->where('do_failed', true)->first();

            if ($failedTransaction){
               // $subscription->completed_order = $subscription->completed_order-1;
                $failedTransaction->update(['do_failed' => false]);
            }
            $subscription->save();
        }else{
            $invoice = $subscription->user->invoices->where('type', 'course')->where('status', 'unpaid')->first();
            
            if (!$invoice){
                $invoice = $this->invoiceRepository->createCourseSubscriptionInvoice($subscription->user, $subscription->plan);
                $invoice->save();
            }
                $invoice->settle();
                $subscription->completed_order = $subscription->completed_order+ 1;
                $this->allocatePayment($invoice, $invoice->transactions()->where('type', 'debit')->first()->amount, "#{$invoice->reference} Debit Order Payment", $method = 'debit');
                $failedTransaction = $invoice->transactions()->where('do_failed', true)->first();

                if ($failedTransaction){
                  //  $subscription->completed_order = $subscription->completed_order-1;
                    $failedTransaction->update(['do_failed' => false]);
                }
                $subscription->save();
            // }else{
            //     $subscription->user->wallet->add($subscription->plan->price, 'debit', Carbon::now(), null, null);
            // }
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
    private function addDebitsEntry($debit_order,$course)
    {
        $debit = Debit::create([
            'number' => $debit_order->number,
            'branch_code' => $debit_order->branch_code,
            'amount' => (int) $course->plan->price,
            'user_id' => $debit_order->user_id,
            'status' => 'paid',
            'type'=>'course',
            'type_id'=> (int) $course->plan->id
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
      
        $refrence = explode("-",$responses['CDVResults']['Result']["CustomerCode"]);
        $account = (int) substr($refrence[0], 5);
        $Subscriptionaccount = (int) substr($refrence[1], 11);

        $subscription  =Subscription::find($Subscriptionaccount);

        $debit_order = DebitOrder::find($account);
        $debit_order->last_debit = Carbon::now();

        if ($subscription->plan->interval == 'month') {
            $debit_order->next_debit_date = Carbon::now()->addMonth()->day($debit_order->billable_date)->startOfDay();
        } else {
            $debit_order->next_debit_date = Carbon::now()->addMonth()->day($debit_order->billable_date)->startOfDay()->addYear(1);
        }

        $debit_order->save();
        $this->TryAllocatePayment($subscription);
    }
}
