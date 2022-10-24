<?php

namespace App\Http\Controllers;

use App\Billing\InvoiceRepository;
use App\Debit;
use App\DebitOrder;
use App\Jobs\NotifyClientOfUnpaidDebitOrder;
use App\Jobs\SendOTP;
use App\OTP;
use App\PeachDo;
use App\Http\Requests;
use App\NumberValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SmsRepository\SmsRepository;
use Illuminate\Support\Facades\Log;
use App\FailedDebitOrder;
use App\Subscriptions\Models\Subscription;
use App\Models\Course;
use Carbon\Carbon;

class PeachWebhooksController extends Controller
{
    private $smsRepo;
    private $peachDo;
    private $numvalidator;
    private $invoiceRepository;

    public function __construct(SmsRepository $smsRepo, PeachDo $peachDo, NumberValidator $numvalidator, InvoiceRepository $invoiceRepository)
    {
        $this->smsRepo = $smsRepo;
        $this->peachDo = $peachDo;
        $this->numvalidator = $numvalidator;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->reverseDebitOrder($request);
        return response()->json(['message', 'success']);
    }

    public function post_store(Request $request)
    {
        $this->reverseDebitOrder($request);
        return response()->json(['message', 'success']);
    }

    public function validateAccount(Request $request)
    {
        return response()->json($this->peachDo->validate($request->account_number, $request->branch_code));
    }

    public function otp(Request $request)
    {
        $otp = rand(10000, 99999);
        $app_name = config('app.name');

        $message = "Use the following OTP to confirm your bank account details: {$otp}. By submitting this form give {$app_name} permission to debit your account on a monthly basis for the amount stipulated.";
        $number = $this->numvalidator->format($request->user()->cell);

        $this->smsRepo->sendSms([
            'message' => $message,
            'number' => $number
        ], $request->user());

        // Send an email with OTP
        $this->dispatch((new SendOTP($request->user(), $otp)));

        // Save OTP to DB
        OTP::create([
            'otp' => $otp,
            'user_id' => $request->user()->id,
            'number' => $number,
        ]);

        return response()->json(['otp' => $otp]);
    }

    /**
     * @param Request $request
     */
    private function reverseDebitOrder(Request $request)
    {
        $json = json_encode(simplexml_load_string($request->response, "SimpleXMLElement", LIBXML_NOCDATA));
        $results = json_decode($json, true);
        \Log::info($json);
        if (isset($results['DebitOrderResults']['Result']['Reference'])) {
            $failedDebitOrder = FailedDebitOrder::create(['response'=>@$json,'reference'=>@$results['DebitOrderResults']['Result']['Reference']]);
            $refrence = explode("-",$results['DebitOrderResults']['Result']['Reference']);
            $debit_id = substr($results['DebitOrderResults']['Result']['Reference'], 5);
            if(count($refrence) == 2){
                $debit_id = (int) substr($refrence[0], 5);
            }
            $do = DebitOrder::find($debit_id);

            $do->update([
                'active' => false,
                'bill_at_next_available_date' => true
            ]);

            $do->save();

            $this->sendNotification($do);

            $debit = Debit::where('batch_id', $results['BatchCode'])->where('number', $do->number)->first();

            if($debit){
                $debit->update([
                    'status' => 'unpaid',
                    'reason' => $results['DebitOrderResults']['Result']['ResultMessage']
                ]);
            }
            $invoiceId = $do->getSubscriptionInvoiceId();    
            if(count($refrence) == 2){
                $wasDebitted = FailedDebitOrder::where('reference', @$results['DebitOrderResults']['Result']['Reference'])->where('created_at', '>=', Carbon::now()->startOfMonth())->first();
                $Subscriptionaccount = (int) substr($refrence[1], 11);
                $subscription = Subscription::find($Subscriptionaccount);
                if(!$wasDebitted){

                    
                   // $subscription->completed_order = $subscription->completed_order-1;
                    $subscription->save();
                }
                     $invoiceId = $subscription->invoice_id;
               
                
            }
            $array = $do->getInvoiceandCourse($results['DebitOrderResults']['Result']['Reference']);
            if(count($array)>2)
            {
                if($array['invoice_id']>0){
                    $invoiceId = $array['invoice_id'];
                }
            }
            $this->invoiceRepository->addDebitTransactionToInvoice($invoiceId);
            
        } else {
            foreach ($results['DebitOrderResults']['Result'] as $result) {
            try{


                $failedDebitOrder = FailedDebitOrder::create(['response'=>@$json,'reference'=>@$result['Reference']]);
                $refrence = explode("-",$result['Reference']);
                $debit_id = substr($result['Reference'], 5);
                if(count($refrence) == 2){
                    $debit_id = (int) substr($refrence[0], 5);
                }
                $do = DebitOrder::find($debit_id);

                $do->update([
                    'active' => false,
                    'bill_at_next_available_date' => true
                ]);

                $do->save();

                $this->sendNotification($do);
                $debit = Debit::where('batch_id', $results['BatchCode'])->where('number', $do->number)->first();
                if($debit){
                    $debit->update([
                        'status' => 'unpaid',
                        'reason' => $result['ResultMessage']
                    ]);
                }
                $invoiceId = $do->getSubscriptionInvoiceId();    
                if(count($refrence) == 2){
                    $wasDebitted = FailedDebitOrder::where('reference', @$result['Reference'])->where('created_at', '>=', Carbon::now()->startOfMonth())->first();
                    $Subscriptionaccount = (int) substr($refrence[1], 11);
                    $subscription = Subscription::find($Subscriptionaccount);
                        
                    if(!$wasDebitted){
                        //$subscription->completed_order = $subscription->completed_order-1;
                        $subscription->save();
                    }
                     $invoiceId = $subscription->invoice_id;
                    
                }
                $array = $do->getInvoiceandCourse($result['Reference']);
                if(count($array)>2)
                {
                    if($array['invoice_id']>0){
                        $invoiceId = $array['invoice_id'];
                    }
                }
                $this->invoiceRepository->addDebitTransactionToInvoice($invoiceId);
                }catch(\Exception $e){
                    \Log::info($e);
                }
            }
        }
    }

    public function sendNotification($debitOrder)
    {
        $debitOrder->update([
            'active' => false,
            'bill_at_next_available_date' => true
        ]);
        $debitOrder->save();

        $this->dispatch((new NotifyClientOfUnpaidDebitOrder($debitOrder)));
    }
}
