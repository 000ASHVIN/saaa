<?php

namespace App\Http\Controllers;

use App\InvoiceOrder;
use App\Models\Course;
use App\NumberValidator;
use App\Repositories\SmsRepository\SmsRepository;
use App\Video;
use App\Http\Requests;
use GuzzleHttp\Client;
use App\AppEvents\Event;
use App\Billing\Invoice;
use Illuminate\Http\Request;
use App\Subscriptions\Models\Plan;
use App\Http\Controllers\Controller;
use App\Events\InstantEftNotificationReceived;

class InstantEftController extends Controller
{
    private $client;
    private $smsRepository;

    public function __construct(Client $client, SmsRepository $smsRepository)
    {
        $this->client = $client;
        $this->smsRepository = $smsRepository;
    }

    public function success(Request $request)
    {
        $this->fireEvent($request);
        $message = 'Your Instant EFT payment was successful. Kind Regards, ' . config('app.name');
        $this->SendNotification($message);
        return view('instant_eft.success');
    }

    public function failed(Request $request)
    {
        event(new InstantEftNotificationReceived($request['payment_key'], false));
        $message = 'Your Instant EFT payment was unsuccessful. Kind Regards, ' . config('app.name');
        $this->SendNotification($message);
        return view('instant_eft.failed');
    }

    public function cancelled(Request $request)
    {
        event(new InstantEftNotificationReceived($request['payment_key'], false));
        $message = 'Your Instant EFT payment was cancelled. Kind Regards, ' . config('app.name');
        $this->SendNotification($message);
        return view('instant_eft.cancelled');
    }

    public function notify(Request $request)
    {
        event(new InstantEftNotificationReceived($request['payment_key'], (bool)$request['success']));
    }
    public function whnotify(Request $request)
    {
        \Log::info($request);
    }

    public function store(Request $request)
    {

        $token = $this->client->request('POST', 'https://eft.ppay.io/api/v1/token', [
            'auth' => [env('INSTANT_USER'), env('INSTANT_PASS')],
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        if($request->donation) {
            return $this->getDonationEftLink($token, $request);
        }
        if($request->plan) {
            return $this->getSubscriptionEftLink($token, $request);
        }

        if($request->event) {
            return $this->getEventRegistrationEftLink($token, $request);
        }

        if($request->store) {
            return $this->getCheckoutEftLink($token, $request);
        }

        if($request->invoice) {
            return $this->getInvoiceEftLink($token, $request);
        }

        if($request->order) {
            return $this->getInvoiceOrderEftLink($token, $request);
        }

        if($request->video) {
            return $this->getVideoEftLink($token, $request);
        }

        if($request->course) {
            return $this->getCourseEftLink($token, $request);
        }
    }

    public function getVideoEftLink($token, $request)
    {
        $video = Video::find($request->video);
        $donation = $request->donation_amount?$request->donation_amount:0;

        $response = $this->client->request('POST', 'https://eft.ppay.io/api/v1/payment-key', [
            'auth' => [env('INSTANT_USER'), env('INSTANT_PASS')],
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'token' => json_decode($token->getBody()->getContents())->token,
                'amount' => $video->amount + $donation,
                'merchant_reference' => "WOD-{$request->user()->id}-{$video->id}",
                'notify_url' => url('/instant-eft/notify'),
                'success_url' => url('/instant-eft/success'),
                'error_url' => url('/instant-eft/failed'),
                'cancel_url' => url('/instant-eft/cancelled')
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());

        return response()->json([
            'link' => $res->url,
            'key' => $res->key
        ]);
    }

    public function getCourseEftLink($token, $request)
    {
        $course = Course::find($request->course);

        $discount  = 0;
        if ($request->option === 'monthly'){
            $amount = $course->monthly_enrollment_fee;
        }else{
            $amount = $course->yearly_enrollment_fee;
            if ($request->option === 'yearly'){
                $discount = $course->annual_discount;
                if($course->type_of_course == 'semester'){
                if($request->course_type == 'partially')
                {
                    $amount = $course->semester_price;
                }elseif($request->course_type == 'full'){
                    $amount = ($course->semester_price)*($course->no_of_semesters);
                }
                }
            }
        }
		$amount = $amount-$discount;

        if($request->donation_amount) {
            $amount = $amount + $request->donation_amount;
        }
        
        $response = $this->client->request('POST', 'https://eft.ppay.io/api/v1/payment-key', [
            'auth' => [env('INSTANT_USER'), env('INSTANT_PASS')],
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'token' => json_decode($token->getBody()->getContents())->token,
                'amount' => $amount,
                'merchant_reference' => "COURSE-{$course->reference}-{$request->user()->id}",
                'notify_url' => url('/instant-eft/notify'),
                'success_url' => url('/instant-eft/success'),
                'error_url' => url('/instant-eft/failed'),
                'cancel_url' => url('/instant-eft/cancelled')
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());

        return response()->json([
            'link' => $res->url,
            'key' => $res->key
        ]);
    }

    public function getDonationEftLink($token, $request)
    {
        $amount = $request->amount;

        $response = $this->client->request('POST', 'https://eft.ppay.io/api/v1/payment-key', [
            'auth' => [env('INSTANT_USER'), env('INSTANT_PASS')],
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'token' => json_decode($token->getBody()->getContents())->token,
                'amount' => $amount,
                'merchant_reference' => "Donation-".time(),
                'notify_url' => url('/instant-eft/notify'),
                'success_url' => url('/instant-eft/success'),
                'error_url' => url('/instant-eft/failed'),
                'cancel_url' => url('/instant-eft/cancelled')
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());

        return response()->json([
            'link' => $res->url,
            'key' => $res->key
        ]);
    }

    public function getInvoiceEftLink($token, $request)
    {
        $invoice = Invoice::find($request->invoice);

        $response = $this->client->request('POST', 'https://eft.ppay.io/api/v1/payment-key', [
            'auth' => [env('INSTANT_USER'), env('INSTANT_PASS')],
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'token' => json_decode($token->getBody()->getContents())->token,
                'amount' => $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount'),
                'merchant_reference' => "INV-{$request->user()->id}-{$invoice->id}",
                'notify_url' => url('/instant-eft/notify'),
                'success_url' => url('/instant-eft/success'),
                'error_url' => url('/instant-eft/failed'),
                'cancel_url' => url('/instant-eft/cancelled')
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());

        return response()->json([
            'link' => $res->url,
            'key' => $res->key
        ]);
    }

    public function getInvoiceOrderEftLink($token, $request)
    {
        $order = InvoiceOrder::find($request->order);

        $response = $this->client->request('POST', 'https://eft.ppay.io/api/v1/payment-key', [
            'auth' => [env('INSTANT_USER'), env('INSTANT_PASS')],
            'headers' => [
                'Accept' => 'application/json'
            ],

            'form_params' => [
                'token' => json_decode($token->getBody()->getContents())->token,
                'amount' => $order->total - $order->discount,
                'merchant_reference' => "PO-{$request->user()->id}-{$order->id}",
                'notify_url' => url('/instant-eft/notify'),
                'success_url' => url('/instant-eft/success'),
                'error_url' => url('/instant-eft/failed'),
                'cancel_url' => url('/instant-eft/cancelled')
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());

        return response()->json([
            'link' => $res->url,
            'key' => $res->key
        ]);
    }

    public function getCheckoutEftLink($token, $request)
    {
        $response = $this->client->request('POST', 'https://eft.ppay.io/api/v1/payment-key', [
            'auth' => [env('INSTANT_USER'), env('INSTANT_PASS')],
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'token' => json_decode($token->getBody()->getContents())->token,
                'amount' => $request->total,
                'merchant_reference' => "STORE-{$request->user()->id}",
                'notify_url' => url('/instant-eft/notify'),
                'success_url' => url('/instant-eft/success'),
                'error_url' => url('/instant-eft/failed'),
                'cancel_url' => url('/instant-eft/cancelled')
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());

        return response()->json([
            'link' => $res->url,
            'key' => $res->key
        ]);
    }

    public function getEventRegistrationEftLink($token, $request)
    {
        $event = Event::find($request->event);

        $response = $this->client->request('POST', 'https://eft.ppay.io/api/v1/payment-key', [
            'auth' => [env('INSTANT_USER'), env('INSTANT_PASS')],
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'token' => json_decode($token->getBody()->getContents())->token,
                'amount' => $request->total,
                'merchant_reference' => "EVENT-{$request->user()->id}-{$event->id}",
                'notify_url' => url('/instant-eft/notify'),
                'success_url' => url('/instant-eft/success'),
                'error_url' => url('/instant-eft/failed'),
                'cancel_url' => url('/instant-eft/cancelled')
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());

        return response()->json([
            'link' => $res->url,
            'key' => $res->key
        ]);
    }

    public function getSubscriptionEftLink($token, $request)
    {
        $plan = Plan::find($request->plan);

        if ($request->bf && $request->bf == true){
            $plan->price = $plan->bf_price;
        }
        $staff = count($request->staffs)+1;
        if ($plan->pricingGroup->count()) {
            $plan->price = $plan->price * ($staff);
            foreach ($plan->pricingGroup as $pricing) {
                if ((int) $pricing->min_user <= $staff && (int) $pricing->max_user >= $staff) {
                    $plan->price = $pricing->price * ($staff);
                }
            }
            $max = $plan->pricingGroup->max('max_user');
            if ($staff > $max) {
                $priceGroup = $plan->pricingGroup->where('max_user', $max)->first();

                if ($priceGroup) {
                    $plan->price = $priceGroup->price * ($staff);
                }
            }
            foreach ($plan->pricingGroup as $pricing) {
                if ((int) $pricing->min_user < $staff && (int) $pricing->max_user == 0) {
                    $plan->price = $pricing->price * ($staff);
                }
            }   
        }

        $amount = $plan->price;
        if($request->donation_amount) {
            $amount = $amount + $request->donation_amount;
        }

        $response = $this->client->request('POST', 'https://eft.ppay.io/api/v1/payment-key', [
            'auth' => [env('INSTANT_USER'), env('INSTANT_PASS')],
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'token' => json_decode($token->getBody()->getContents())->token,
                'amount' => $amount,
                'merchant_reference' => "CPD-{$request->user()->id}-{$plan->id}",
                'notify_url' => url('/instant-eft/notify'),
                'success_url' => url('/instant-eft/success'),
                'error_url' => url('/instant-eft/failed'),
                'cancel_url' => url('/instant-eft/cancelled')
            ]
        ]);

        $res = json_decode($response->getBody()->getContents());

        return response()->json([
            'link' => $res->url,
            'key' => $res->key
        ]);
    }

    public function SendNotification($message)
    {
        if (auth()->user()->cell) {
            $numberValidator = new NumberValidator();
            $number = $numberValidator->format(auth()->user()->cell);

            $this->smsRepository->sendSms([
                'message' => $message,
                'number' => $number
            ], auth()->user());
        };
    }

    private function fireEvent(Request $request)
    {
        event(new InstantEftNotificationReceived($request['payment_key'], (bool)$request['success']));
    }
}