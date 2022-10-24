<?php

namespace App\Http\Controllers;

use App\Card;
use App\Peach;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Repositories\WalletRepository\WalletRepository;
use Carbon\Carbon;
use App\Http\Requests;
use App\Billing\Invoice;
use App\Billing\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Billing\CreditCardBillingRepository;
use App\Services\Invoicing\PdfGenerateInvoice;
use Illuminate\Support\Facades\App;
use App\Video;
use App\Models\Course;
use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use App\AppEvents\Ticket;
use App\AppEvents\DietaryRequirement;
use App\AppEvents\Extra;
use App\InvoiceOrder;
use App\AppEvents\Date;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;

class InvoicesController extends Controller
{
    protected $generate;
    protected $creditCardBillingRepository;
    protected $peach;
    private $walletRepository;
    private $sendInvoiceRepository;

    public function __construct(PdfGenerateInvoice $generate, CreditCardBillingRepository $creditCardBillingRepository, Peach $peach, WalletRepository $walletRepository, SendInvoiceRepository $sendInvoiceRepository)
    {
        $this->generate = $generate;
        $this->creditCardBillingRepository = $creditCardBillingRepository;
        $this->peach = $peach;
        $this->walletRepository = $walletRepository;
        $this->sendInvoiceRepository = $sendInvoiceRepository;
    }

    public function show($id)
    {
        $invoice = Invoice::find($id);

        if ($invoice->user->id != auth()->user()->id && !auth()->user()->is('super|accounts|accounts-administrator')) {
            return redirect()->route('dashboard');
        }

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('invoices.view', compact('invoice'));
        return $pdf->inline();
    }

    public function getSettle($id)
    {
        if(request()->has('threeDs'))
            $this->handleThreeDs(request());

        $invoice = Invoice::with('transactions')->findOrFail($id);

        if ($invoice->balance <= 0) {
            alert()->error('You cannot settle an invoice with no outstanding balance', 'Error');
            return redirect()->route('dashboard');
        }

        if ($invoice->user->id != auth()->user()->id && !auth()->user()->hasRole('admin')) {
            return redirect()->route('dashboard');
        }

        $paymentsTotal = $invoice->transactions->where('type', 'credit')->sum('amount');
        $invoice->balance = $invoice->total - $paymentsTotal;
        $invoice->save();

        return view('invoices.settle', compact('invoice', 'paymentsTotal'));
    }

    public function postSettle(Requests\SettleInvoiceRequest $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($request->paymentOption == 'eft') {
            $invoice->settle();
            $this->allocatePayment($invoice, $invoice->balance, 'instant_eft' ,"Instant EFT Payment");
            $this->allocateProduct($invoice);
            $this->allocateVideoOfPlanFeatures($invoice);
            alert()->success('Your invoice has been settled.', 'Success');
            return response()->json(['message' => 'success'], 200);
        }

        if ($request->paymentOption == 'cc') {

            $card = Card::find($request->card);

            $payment = $this->peach->charge(
                $card->token,
                $invoice->balance,
                '#' . $invoice->reference,
                $invoice->reference
            );

            if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {
                $invoice->settle();
                $this->allocatePayment($invoice, $invoice->balance, 'cc' ,"Credit Card Payment");
                $this->allocateProduct($invoice);
                $this->allocateVideoOfPlanFeatures($invoice);
                alert()->success('Your invoice has been settled.', 'Success');
                return response()->json(['message' => 'success'], 200);
            } else {
                return response()->json([
                    'errors' => [
                        'card' => $payment['result']['description']
                    ]
                ], 422);
            }            
        }

        if ($request->paymentOption == 'wallet') {
            $this->walletRepository->payInvoice(auth()->user()->id, $invoice->fresh()->id);
            $this->sendInvoiceRepository->sendInvoice(auth()->user(), $invoice->fresh());
            $this->allocateVideoOfPlanFeatures($invoice);
            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json([
                'errors' => 'Not Enought Credit'
            ], 422);
        }
        return "Done";
    }

    // Allocate as per class
    public function allocateProduct($invoice)
    {
        $invoiceData = $invoice->items;
        foreach($invoiceData as $item){
            $productData = $item->productable;
            $extra_details = json_decode($item->extra_details);
            if(isJson($item->extra_details)){
            if(get_class($productData) == get_class(new Video()))
            {
                $this->allocateVideo($invoice,$productData);
            }
            else if(get_class($productData) == get_class(new Course()))
            {
                $this->allocateCourse($invoice,$productData);
            }
            else if(get_class($productData) == get_class(new Event()))
            {
                $this->allocateEvent($invoice,$productData,$extra_details);
            }
            }
        }
    }

    public function allocateVideoOfPlanFeatures($invoice)
    {
        $videoIds = [60, 61, 62, 64];
        $plan = Plan::find($invoice->items->where('type', 'subscription')->first()->item_id);
        if($invoice->type == 'subscription' && $plan) {
            $videos = Video::whereIn('id', $videoIds)->get();
            $user = auth()->user();
            $owned_webinars = $user->webinars->pluck('id')->toArray();
            foreach($videos as $video){
                if(!in_array($video->id,$owned_webinars)) {
                    $user->webinars()->save($video);
                }  
            }
        }
    }

    // Allocate Video
    public function allocateVideo($invoice,$productData)
    {
        if(!$invoice->user->webinars->contains($productData->id)){
            $invoice->user->webinars()->save($productData);
        }
    }

    // Allocate Course
    public function allocateCourse($invoice,$productData)
    {
        $user = $invoice->user;
        if(!$user->courses->contains($productData->id)){
            $user->courses()->save($productData);
        }
    }

    // Allocate Event
    public function allocateEvent($invoice,$productData,$extra_details)
    {
        $user = $invoice->user;
        if(!$user->isRegisteredForEvent($productData)){
            return true;
        }
        $event = $productData;
        $venue = Venue::findOrFail($extra_details->venue);
        $pricing = Pricing::where('event_id', $event->id)->where('venue_id', $venue->id)->first();
        $order = collect();
        if($extra_details->order_id)
        {
            $order = InvoiceOrder::find($extra_details->order_id);
        }
        // Create Event Ticket
        $ticket = $this->createTicket($user,$extra_details, $pricing,$order);
        // $ticket = $this->createTicket($extra_details, $pricing, $order);

         // Set Extras
        if (count($extra_details->get('extras', [])) >= 1) {
            $this->setExtras($ticket, $order, $extra_details);
        }

        // Set Dietary
        if ($extra_details->dietary > 0) {
            $this->setDietary($order, $extra_details->dietary, $extra_details->dates);
        }
        $this->setDates($extra_details->dates, $ticket);
        
    }
    public function setDates($dates, $ticket)
    {
        foreach ($dates as $date) {
            $toAdd = Date::find($date['id']);
            $ticket->dates()->attach($toAdd);
        }
    }

    public function setExtras($ticket, $order, $request)
    {
        foreach ($request->extras as $extra) {
            $toAdd = Extra::find($extra['id']);
            if ($toAdd->price > 0)
                $this->addExtraToInvoice($toAdd, $order, $ticket->event->name);
            $ticket->extras()->attach($toAdd);
        }
    }

    public function setDietary($order, $dietary, $dates)
    {
        $multiplier = count($dates);
        $toAdd = DietaryRequirement::find($dietary);
        if ($toAdd->price > 0) {
            for ($i=1; $i <= $multiplier; $i++) {
                $this->addDietaryToInvoice($toAdd, $order);
            }
        }
    }

    // Generate a new ticket
    public function createTicket($user,$extra_details, $pricing,$order)
    {
        $ticket = new Ticket;
        $ticket->code = str_random(20);
        $ticket->name = $pricing->name;
        $ticket->description = $pricing->description;
        $ticket->first_name = $user->first_name;
        $ticket->last_name = $user->last_name;
        $ticket->user_id = $user->id;
        $ticket->event_id = $extra_details->event;
        $ticket->venue_id = $extra_details->venue;
        $ticket->pricing_id = $extra_details->pricing;
        $ticket->invoice_order_id = $order->id;
        $ticket->dietary_requirement_id = $extra_details->dietary;
        $ticket->invoice_order_id = '111';
        $ticket->dietary_requirement_id = '1';
        $ticket->email = $user->email;
        $ticket->save();
        return $ticket;
    }

    public function allocatePayment($invoice, $amount, $method, $description)
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
     * Handle 3DS on Return
     */
    protected function handleThreeDs($request)
    {
        $payment = $this->peach->fetchPayment($request->id);

        if(! Card::where('token', $payment->registrationId)->exists() && $payment->successful()) {
            $card = new Card([
                'token' => $payment->registrationId,
                'brand' => $payment->paymentBrand,
                'number' => $payment->card['bin'] . '******' . $payment->card['last4Digits'],
                'exp_month' => $payment->card['expiryMonth'],
                'exp_year' => $payment->card['expiryYear']
            ]);

            auth()->user()->cards()->save($card);

            if(count(auth()->user()->cards) == 1) {
                auth()->user()->update([
                    'primary_card' => $card->id
                ]);
            }

            alert()->success('Credit card added successfully.', 'Success');
        } else {
            alert()->error('Credit card already added or invalid.', 'Could not save credit card');
        }
    }
}
