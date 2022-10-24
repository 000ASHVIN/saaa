<?php

namespace App\Http\Controllers\Events;

use App\ActivityLog;
use App\InvoiceOrder;
use App\Jobs\SendEventConfirmation;
use App\Jobs\SendEventTicketInvoiceJob;
use App\Jobs\SendEventTicketOrderJob;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Repositories\InvoiceOrder\InvoiceOrderRepository;
use App\Repositories\WalletRepository\WalletRepository;
use Calendar;
use DateTime;
use DB;
use App\Card;
use App\Peach;
use App\Charge;
use Carbon\Carbon;
use App\Billing\Item;
use App\Http\Requests;
use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\Billing\Invoice;
use App\Billing\Payment;
use App\AppEvents\Ticket;
use App\AppEvents\Pricing;
use App\Mailers\UserMailer;
use App\AppEvents\PromoCode;
use Illuminate\Http\Request;
use App\AppEvents\EventRepository;
use App\Http\Controllers\Controller;
use App\AppEvents\DietaryRequirement;
use App\Billing\CreditCardBillingRepository;
use App\Http\Requests\EventRegistrationRequest;
use Spatie\CalendarLinks\Link;
use App\Donation;
use App\Subscriptions\Models\Plan;

use App\Users\User;

class EventsController extends Controller
{
    protected $eventRepository;
    protected $creditCardBillingRepository;
    protected $products;
    private $peach;
    private $sendInvoiceRepository;
    private $walletRepository;
    private $invoiceOrderRepository;
    public function __construct(EventRepository $eventRepository,
                                CreditCardBillingRepository $creditCardBillingRepository,
                                Peach $peach, SendInvoiceRepository $sendInvoiceRepository,
                                WalletRepository $walletRepository,
                                InvoiceOrderRepository $invoiceOrderRepository
    )

    {
        $this->middleware('auth', ['except' => ['index', 'show', 'getEvent', 'search_events','past']]);
        $this->eventRepository = $eventRepository;
        $this->creditCardBillingRepository = $creditCardBillingRepository;
        $this->peach = $peach;
        $this->sendInvoiceRepository = $sendInvoiceRepository;
        $this->walletRepository = $walletRepository;
        $this->invoiceOrderRepository = $invoiceOrderRepository;
    }

    /**
     * Display a listing of all the events that are published.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = $this->eventRepository->getUpcoming();
        return view('events.index', compact('events'));
    }

    public function past()
    {
        $events = $this->eventRepository->getPast();
        return view('events.past', compact('events'));
    }

    /**
     * Display the specified event.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     * @throws \Spatie\CalendarLinks\Exceptions\InvalidLink
     */
    public function show($slug)
    {
        $event = $this->eventRepository->findBySlug($slug);
        if($event->reference_id!=null)
        {
            alert()->info('That event is no longer open for registration.');
            return redirect('/events');
        }
        unset($event->venues);

        $event->venues = $event->venues;
        $promoCodes = $event->promoCodes;
        PromoCode::clear();

        $from = DateTime::createFromFormat('Y-m-d H:i', date_format($event->start_date, 'Y-m-d').$event->Start_time);
        $to = DateTime::createFromFormat('Y-m-d H:i', date_format($event->end_date, 'Y-m-d').$event->end_time);

        $link = Link::create(str_replace('\'/', '', preg_replace('/[^A-Za-z0-9\-]/', '', $event->name)), $from, $to)
            ->address(config('app.name'))
            ->description(htmlentities($event->short_description));

        $user = auth()->user();
        if($user) {
            $this->filterVenuePricings($user, $event);
        }
        
        $eventPlans = $this->eventPlans($event);

        $cpdSubscriptionPlans = Plan::where('invoice_description','NOT LIKE','%Course:%')->where('inactive', false)->get();

        return view('events.show', compact('event', 'promoCodes', 'isPastEvent', 'link','eventPlans','cpdSubscriptionPlans'));
    }

    public function eventPlans($event) {
        $pricings = $event->pricings->pluck('id');
        $fearturePricing = DB::table('feature_pricing')->select('feature_id')->whereIn('pricing_id', $pricings)->groupBy('feature_id')->get();

        $featureId = [];
        foreach($fearturePricing as $feature) {
            $featureId[] = $feature->feature_id;
        }
        $fearturePlan = DB::table('feature_plan')->select('plan_id')->whereIn('feature_id', $featureId)->get();

        $planId = [];
        foreach($fearturePlan as $plan) {
            $planId[] = $plan->plan_id;
        }

        $plans = Plan::whereIn('id', $planId)->get();
        return $plans;
    }

    /**
     * Display the specified event.
     *
     * @param  integer $id
     * @return mixed
     */
    public function getEvent($id)
    {
        return $this->eventRepository->find($id);
    }

    /**
     * Display page to register for an event.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param $slug
     */
    public function getRegister(Request $request, $slug)
    {
        if(request()->has('threeDs'))
            $this->handleThreeDs(request());

        $user = User::with('body','body.pricings')->where('id',auth()->id())->first();
        // Check if user account is suspended, if yes the do not allow.
        if ($user->debt_arrangement == false){
            if ($user->status == 'suspended' || $user->force_suspend == true){
                alert()->info(
                    'Please check your account outstanding balance before trying to register for this event. <hr>
                <a href="/dashboard/statement" style="font-weight: bold; color: red;">View Account Statement</a>
                ', 'Account '.$user->status
                )->persistent('close');
                return back();
            }
        }

        $event = $this->eventRepository->findBySlug($slug, $request);

        if (!$event->is_active) {
            alert()->info('That event is no longer open for registration.');
            return redirect()->route('dashboard.events');
        }

        $subscription = $user->subscription('cpd');
        if ($subscription && $subscription->plan->interval == 'year' && $event->start_date > $subscription->ends_at) {
            alert()->info("You can't book an event which will start after your subscription ends")->persistent('close');
            return  redirect('/events');
        }

        if ($user->isRegisteredForEvent($event)) {
            alert()->info('You are already registered to attend "' . $event->name . '".', 'Info')->persistent('Close');
            return redirect()->route('dashboard.events');
        }

        if(!$event->userCanRegister())
        {
            alert()->info('That event is no longer open for registration.');
            return  redirect('/events');
        }
        $promoCodes = $event->promoCodes;
        $dietaryRequirements = DietaryRequirement::all();

        $this->filterVenuePricings($user, $event);

        return view('events.register', compact('event', 'dietaryRequirements', 'promoCodes'));
    }

    public function postRegister(EventRegistrationRequest $request, UserMailer $mailer)
    {
        $response = DB::transaction(function () use($request, $mailer) {
            //  Find Pricing
            $pricing = Pricing::find($request->pricing);

            // Generate order
            $order = $this->generateOrder(auth()->user(), $pricing);
            $request->merge(['order_id' => $order->id]);
            // Create Event Ticket
            $ticket = $this->createTicket($request, $pricing, $order);

            // Set Extras
            if (count($request->get('extras', [])) >= 1) {
                $this->setExtras($ticket, $order, $request);
            }

            // Set Dietary
            if ($request->dietary > 0) {
                $this->setDietary($order, $request->dietary, $request->dates);
            }

            $this->setDonations($order, $request->donations);

            $order->addItems($this->products);
            $order->autoUpdateAndSave();

            // Set Ticket Dates
            $this->setDates($request->dates, $ticket);

            if($order->total - $order->discount <= 0){
                $invoice = $this->invoiceOrderRepository->processCharge($order, 'Applied', 'Discount');
                // $this->dispatch((new SendEventTicketOrderJob($order->fresh())));
                // $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
                $this->dispatch((new SendEventConfirmation($ticket->user, $ticket->fresh())));
                PromoCode::clear();
                $invoice = $invoice->fresh();
                $items = $invoice->items;
                $this->setActivityForRegisteringEvent();
                return response()->json(['message' => 'success', 'invoice' => $invoice], 200);
            }

            if ($request->paymentOption == 'po'){
                $this->dispatch((new SendEventTicketOrderJob($order->fresh())));
                $this->dispatch((new SendEventConfirmation($ticket->user, $ticket->fresh())));
                PromoCode::clear();
                $this->setActivityForRegisteringEvent();
                return response()->json(['message' => 'success'], 200);
            }


            if ($request->paymentOption == 'eft'){
                $invoice = $this->invoiceOrderRepository->processCharge($order, 'instant_eft', 'Instant EFT Payment');
                $this->dispatch((new SendEventTicketOrderJob($order->fresh())));
                $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
                $this->dispatch((new SendEventConfirmation($ticket->user, $ticket->fresh())));
                PromoCode::clear();
                $invoice = $invoice->fresh();
                $items = $invoice->items;
                $this->setActivityForRegisteringEvent();
                return response()->json(['message' => 'success', 'invoice' => $invoice], 200);
            }

            if ($request->paymentOption == 'cc') {
                $card = Card::find($request->card);
                $payment = $this->peach->charge(
                    $card->token,
                    $order->total - $order->discount,
                    'Order #' . $order->reference,
                    $order->reference
                );

                if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {
                    $invoice = $this->invoiceOrderRepository->processCharge($order, 'cc', 'Credit Card Payment');

                    $this->dispatch((new SendEventConfirmation($ticket->user, $ticket->fresh())));
                    $this->dispatch((new SendEventTicketOrderJob($order->fresh())));
                    $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
                    PromoCode::clear();
                    $this->setActivityForRegisteringEvent();
                    return response()->json(['message' => 'success'], 200);

                } else {
                    return response()->json([
                        'errors' => $payment['result']['description']
                    ], 422);
                }
            }

            if ($request->paymentOption == 'wallet') {
                $this->walletRepository->payInvoice(auth()->user()->id, $order->fresh()->id);
                $this->dispatch((new SendEventTicketOrderJob($order->fresh())));
                $this->dispatch((new SendEventConfirmation($ticket->user, $ticket->fresh())));
                PromoCode::clear();
                $this->setActivityForRegisteringEvent();
                return response()->json(['message' => 'success'], 200);
            } else {
                return response()->json([
                    'errors' => 'Not Enought Credit'
                ], 422);
            }
        });

        PromoCode::clear();
        return $response;
    }

    public function setActivityForRegisteringEvent()
    {
        ActivityLog::create([
            'user_id' => (auth()->check()) ? auth()->user()->id : 0,
            'model' => get_class(new Event()),
            'model_id' => (request()->event) ? request()->event : 0,
            'action_by' => 'manually',
            'action' => 'Event Registration',
            'data' => json_encode([request()->except(['terms'])]),
            'request_url' => request()->path()
        ]);
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
            'date' => Carbon::now()->addSeconds(30)
        ]);
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

    public function setDonations(&$order, $donations) {

        if($donations) {
            $order->donation = $donations;
            $order->save();
        }

    }

    public function addExtraToInvoice($extra, $invoice, $event_name)
    {
        $item = new Item;
        $item->type = 'product';
        $item->name = $extra->name;
        $item->description = $event_name;
        $item->price = $extra->price;
        $item->item_id = $extra->id;
        $item->item_type = get_class($extra);
        $item->save();

        $this->products[] = $item;
    }

    public function addDietaryToInvoice($dietary, $invoice)
    {
        $item = new Item;
        $item->type = 'product';
        $item->name = $dietary->name;
        $item->description = 'Dietary Requirement';
        $item->price = $dietary->price;
        $item->item_id = $dietary->id;
        $item->item_type = get_class($dietary);
        $item->save();

        $this->products[] = $item;
    }

    public function createTicket($request, $pricing, $order)
    {
        $ticket = new Ticket;
        $user = auth()->user();
        $ticket->code = str_random(20);
        $ticket->name = $pricing->name;
        $ticket->description = $pricing->description;
        $ticket->first_name = $user->first_name;
        $ticket->last_name = $user->last_name;
        $ticket->user_id = $user->id;
        $ticket->event_id = $request->event;
        $ticket->venue_id = $request->venue;
        $ticket->pricing_id = $request->pricing;
        $ticket->invoice_order_id = $order->id;
        $ticket->dietary_requirement_id = $request->dietary;
        $ticket->email = $user->email;
        $ticket->save();
        return $ticket;
    }

    public function generateInvoice($user, $pricing, $request)
    {
        $invoice = new Invoice;
        $invoice->type = 'event';
        $invoice->setUser($user);
        $invoice->save();

        $item = new Item;
        $item->type = 'ticket';
        $item->name = $pricing->event->name;
        $item->description = $pricing->description . ' - ' . $pricing->venue->name;
        $item->price = $pricing->price;
        $item->discount = ($pricing->getDiscountForUser($user) + $pricing->getPromoCodesDiscount());
        $item->item_id = $pricing->event->id;
        $item->item_type = get_class($pricing->event);
        $item->save();
        
        $this->products[] = $item;

        return $invoice;
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

    /**
     * @param $user
     * @param $event
     */
    public function filterVenuePricings($user, $event)
    {
        if (isset($user->body) && $user->membership_verified == true) {
            foreach ($event->venues as $venue) {
                $pricings = collect();
                $memberBodyPricing = $user->body->pricings->where('venue_id', $venue->id);

                if (count($memberBodyPricing)) {
                    foreach ($memberBodyPricing as $pricing) {
                        if ($venue->pricings->contains($pricing)) {

                            $filtered_pricings = $venue->pricings->reject(function ($pric) use ($pricing, $user) {
                                return $pricing->id != $pric->id;
                            });

                            // Push the pricings to a new collection.
                            $pricings->push($filtered_pricings->first());
                        }
                    }
                    // Update the eloquent realationship with the new pricings.
                    $venue->setRelation('pricings', $pricings);
                }
            }

            // Filter the filtered venues from above.
            foreach ($event->venues as $venue) {
                $newVenuePricings = collect();
                foreach ($venue->pricings as $pricing) {
                    if (count($pricing->bodies)) {
                        if ($pricing->bodies->contains($user->body->id)) {
                            $newVenuePricings->push($pricing);
                        }
                    } else {
                        $newVenuePricings->push($pricing);
                    }
                }
                $venue->setRelation('pricings', $newVenuePricings);
            }

        } elseif (! $user->body || $user->membership_verified == false) {
            foreach ($event->venues as $venue) {


                $filter = $venue->pricings->reject(function ($pric){
                    if (count($pric->bodies)) {
                        return $pric;
                    }
                });

                /*
                 * Let's build a new collection of pricings for this venue...
                 */
                $bodyLessPricings = collect();

                foreach ($filter as $pricing){
                    $bodyLessPricings->push($pricing);
                }

                $venue->setRelation('pricings', $bodyLessPricings);
            }
        }
    }

    public function search_events(Request $request)
    {
        list($venue, $data) = $this->search_function($request);
        $startDateFilter = $this->past_presenter_filter($request, $data);
        $events = $this->venueFilter($venue, $startDateFilter);

        if (count($events)){
            $request->flash();
            alert()->success('We found some events that matched your search criteria', 'Events Found');
            return view('events.search_results', compact('events'));
        }else{
            alert()->error('We did not find any events that matched your search criteria, Please try again', 'No Events found')->persistent('close');
            return redirect(route('events.index'));
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function search_function(Request $request)
    {
        $title = str_replace('--', '-', str_replace(' ', '-', strtolower(preg_replace('/[^a-zA-Z0-9 .]/', '', $request['title']))));
        $start_date = $request['start_date'];
        $type = strtolower($request['type']);
        $venue = ucfirst($request['venue']);

        $data = Event::where(function ($query) use ($title, $start_date, $type) {
            if (strlen($title)) {
                $query->where('slug', 'like', '%' . $title . '%');
            }
            if ($type != null) {
                $query->where('type', $type);
            }
            if ($start_date) {
                $query->where('start_date', 'like', '%' . $start_date . '%');
            }
            
        })->where('is_active', true)->where('is_open_to_public',true)->get();
        return array($venue, $data);
    }

    /**
     * @param Request $request
     * @param $data
     * @return mixed
     */
    public function past_presenter_filter(Request $request, $data)
    {
        if(env('APP_THEME') == 'taxfaculty'){
            if ($request->has('date_filter') && $request['date_filter'] == 'past') {
                $startDateFilter = $data->filter(function ($event) {
                    if ($event->start_date <= Carbon::today()->startOfDay()) {
                        return $event;
                    }
                });
            } else {
                $startDateFilter = $data->filter(function ($event) {
                    if ($event->start_date >= Carbon::today()->startOfDay()) {
                        return $event;
                    }
                });
            }
            return $startDateFilter;
        }else{
            return $data;
        }
    }

    /**
     * @param $venue
     * @param $startDateFilter
     * @return mixed
     */
    public function venueFilter($venue, $startDateFilter)
    {
        if ($venue) {
            $events = $startDateFilter->filter(function ($event) use ($venue) {
                if ($venue == 'Webinar') {
                    if ($event->venues->where('city', '')) {
                        return $event;
                    }
                } else {
                    if ($event->venues->contains('city', $venue)) {
                        return $event;
                    }
                }
            })->sortBy('start_date');;
        } else {
            $events = $startDateFilter->sortBy('start_date');
        }
        return $events;
    }

    public function generateOrder($user, $pricing)
    {
        $order = new InvoiceOrder;
        $order->type = 'event';
        $order->setUser($user);
        $order->save();
        $item = new Item;
        $item->type = 'ticket';
        $item->name = $pricing->event->name;
        $item->description = $pricing->description . ' - ' . $pricing->venue->name;
        $item->price = $pricing->price;
        $item->discount = ($pricing->getDiscountForUser($user) + $pricing->getPromoCodesDiscount() + $pricing->getCPDSubscriberDiscount());
        $item->item_id = $pricing->event->id;
        $item->item_type = get_class($pricing->event);
        $item->save();

        $this->products[] = $item;
        return $order;
    }
}
