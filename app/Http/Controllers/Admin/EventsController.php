<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\DietaryRequirement;
use App\AppEvents\Extra;
use App\Billing\InvoiceRepository;
use App\Billing\Item;
use App\Blog\Category;
use App\InvoiceOrder;
use App\InvoiceOrderItem;
use App\Jobs\SendEventConfirmation;
use App\Jobs\SendEventTicketInvoiceJob;
use App\Jobs\SendEventTicketOrderJob;
use App\Note;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Repositories\InvoiceOrder\InvoiceOrderRepository;
use App\Repositories\Ticket\TicketRepository;
use DB;
use App\DebugLog;
use App\Users\Cpd;
use Carbon\Carbon;
use App\Users\User;
use App\Certificate;
use App\Http\Requests;
use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Venue;
use App\Billing\Invoice;
use App\AppEvents\Ticket;
use App\AppEvents\Pricing;
use Illuminate\Http\Request;
use App\Presenters\Presenter;
use App\AppEvents\EventRepository;
use App\Subscriptions\Models\Plan;
use App\Http\Controllers\Controller;
use Mockery\Exception;
use App\Models\Course;
use DB as DBTable;
use App\Events\CourseSubscibed;
use Session;
use App\Subscriptions\Models\Subscription;

class EventsController extends Controller
{
    protected $eventRepository, $invoiceRepository, $ticketRepository, $sendInvoiceRepository, $invoiceOrderRepository;

    public function __construct(EventRepository $eventRepository,
                                InvoiceRepository $invoiceRepository,
                                TicketRepository $ticketRepository,
                                SendInvoiceRepository $sendInvoiceRepository,
                                InvoiceOrderRepository $invoiceOrderRepository
    )
    {
        $this->eventRepository = $eventRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->ticketRepository = $ticketRepository;
        $this->sendInvoiceRepository = $sendInvoiceRepository;
        $this->invoiceOrderRepository = $invoiceOrderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = $this->eventRepository->all()->with('notifications')->whereNull('reference_id')->orderBy('start_date')->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function venues()
    {
        $events = $this->eventRepository->all()->paginate(10);
        return view('admin.events.venues', compact('events'));
    }

    public function closevenues(Request $request, $id)
    {
        $input = $request->all('is_active');
        $venue = Venue::findorFail($id);
        $venue->fill($input)->save();
        return redirect()->back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all()->pluck('title', 'id');
        $plans = Plan::all()->sortBy('name')->pluck('id', 'name');
        $presenters = Presenter::all()->pluck('name', 'id' );
        return view('admin.event.create', compact('plans', 'presenters', 'categories'));
    }

    public function show($id)
    { 
        $categories = Category::all()->pluck('title', 'id');
        
        $event = Event::find($id);
        unset($event->description);
        unset($event->short_description);
        
        $tickets = DB::table('tickets')->select('tickets.id','tickets.code','tickets.company', 'tickets.first_name', 'tickets.last_name', 'tickets.description', 'tickets.created_at',
            DB::raw('users.id AS ticket_user_id'),
            DB::raw('users.id AS user_id'),
            DB::raw('GROUP_CONCAT(dates.date SEPARATOR ";") AS dates'),
            DB::raw('venues.name AS venue_name'), 
            DB::raw('pricings.name AS pricings_name'),
            DB::raw('invoices.reference AS invoice_reference'),
            DB::raw('invoices.status AS invoice_status'),
            DB::raw('GROUP_CONCAT(dietary_requirements.name SEPARATOR ",") AS dietary_requirement_name'),
            DB::raw('users.email AS user_email'),
            DB::raw('users.first_name AS user_first_name'),
            DB::raw('users.last_name AS user_last_name'),
            DB::raw('users.cell AS user_cell'),
            DB::raw('(invoices.total - invoices.discount)  AS invoice_total'),
            DB::raw('invoices.balance AS invoice_balance'),
            DB::raw('(SELECT plans.name
                        FROM users
                        INNER JOIN subscriptions
                        ON users.id = subscriptions.user_id
                        INNER JOIN plans
                        ON subscriptions.plan_id = plans.id
                        WHERE users.id = ticket_user_id
                        AND subscriptions.name = "cpd"
                        AND subscriptions.ends_at>="'.Carbon::now().'"
                        AND subscriptions.deleted_at IS NULL
                        ORDER BY subscriptions.created_at DESC
                        LIMIT 1
                    ) AS subscription_name')
            )
            ->where('tickets.event_id', $event->id)
            ->where(function($query){
                $query->where('tickets.deleted_at','=','0000-00-00 00:00:00')
                ->orWhereNull('tickets.deleted_at');
            })
            ->leftJoin('users', 'tickets.user_id', '=', 'users.id')
            ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id')
            ->leftJoin('venues', 'tickets.venue_id', '=', 'venues.id')
            ->leftJoin('dates', 'venues.id', '=', 'dates.venue_id')
            ->leftJoin('extra_ticket', 'extra_ticket.ticket_id', '=', 'tickets.id')
            ->leftJoin('extras', 'extra_ticket.extra_id', '=', 'extras.id')
            ->leftJoin('pricings', 'tickets.pricing_id', '=', 'pricings.id')
            ->leftJoin('dietary_requirements', 'tickets.dietary_requirement_id', '=', 'dietary_requirements.id')
            ->leftJoin('invoices', 'invoices.id', '=', 'tickets.invoice_id')
            ->groupBy('tickets.id')
            ->get();
        
        foreach($tickets as $key => $ticket) {

            $newTicket = $ticket;
            $newTicket->code = preg_replace('/[^A-Za-z0-9\. -]/', '', $newTicket->code);
            $newTicket->company = preg_replace('/[^A-Za-z0-9\. -]/', '', $newTicket->company);
            $newTicket->last_name = preg_replace('/[^A-Za-z0-9\. -]/', '', $newTicket->last_name);
            $newTicket->first_name = preg_replace('/[^A-Za-z0-9\. -]/', '', $newTicket->first_name);
            $newTicket->description = preg_replace('/[^A-Za-z0-9\. -]/', '', $newTicket->description);

            $newTicket->invoice_total = empty($newTicket->invoice_total)?0:$newTicket->invoice_total;
            $newTicket->invoice_balance = empty($newTicket->invoice_balance)?0:$newTicket->invoice_balance;
            $newTicket->invoice_status = empty($newTicket->invoice_status)?'None':$newTicket->invoice_status;
            $newTicket->dietary_requirement_name = empty($newTicket->dietary_requirement_name)?'None':$newTicket->dietary_requirement_name;
            $newTicket->invoice_reference = empty($newTicket->invoice_reference)?'None':$newTicket->invoice_reference;
            
            $newTicket->subscription = "";

            /*
            if($newTicket->user_id) {
                $user = User::find($newTicket->user_id);
                if($user && $user->subscribed('cpd')) {
                    $newTicket->subscription = $user->subscription()->plan->name;
                }
            }
            $newTicket->subscription = empty($newTicket->subscription)?'None':$newTicket->subscription;
            */

            $newTicket->subscription = empty($newTicket->subscription_name)?'None':$newTicket->subscription_name;

            // Combine dates
            if (isset($ticket->dates) && !empty($ticket->dates)){
                $dates = explode(";",$ticket->dates);
                foreach($dates as $key=>$date) {
                    $dates[$key]=Carbon::parse($date)->toFormattedDateString();
                }
                $newTicket->dates = implode(";",$dates);
                $tickets[$key] = $newTicket;
            }
        }
        
        $event->tickets = $tickets;        
        return view('admin.events.show', compact('event', 'categories'));
    }

    public function getAssignToPlans()
    {
        $events = Event::with(['venues', 'venues.dates'])->get();
        $plans = Plan::where('price', '>', 0)->get();

        $freePlan = [
            'id' => '0',
            'name' => 'Free Plan',
            'description' => "None",
            'price' => null,
            'interval' => 'year',
            'interval_count' => 1,
            'trial_period_days' =>0,
            'sort_order' => 0,
            'inactive' => false,
            'cpd_hours' => 0,
            'is_practice' => 0,
            'is_custom' => 0
        ];

        $plans->push($freePlan);
        return view('admin.events.assign-to-plans', compact('events', 'plans'));
    }

    public function register($memberId, Request $request)
    {
        $eventsData = $request->eventsObject;

        $user = User::find($memberId);
        $successfull_tickets = [];
        $not_registerd_events = [];
        if ($request->has('generate_invoice') && $request->generate_invoice){
            $invoice = new Invoice;
            $invoice->type = 'event';
            $invoice->setUser($user);
            $invoice->save();
        }

        foreach($eventsData as $eventData) {
            
            $event = Event::findOrFail($eventData['selectedEventId']);
            $venue = Venue::findOrFail($eventData['selectedVenueId']);
            $date = Date::findOrFail($eventData['selectedDateId']);
            $pricing = Pricing::findOrFail($eventData['selectedPricingId']);

            if (!$user->isRegisteredForEvent($event)) {
        // Create the new ticket.
        // DB::transaction(function () use($request, $pricing, $venue, $event, $user, $date){
            $ticket = $this->ticketRepository->createTicket($user, $pricing, $venue, $event);
            $user->tickets()->save($ticket);
            $ticket->dates()->save($date);
            $successfull_tickets[] = $ticket->id;
            $note = new Note([
                'type' => 'event_registration',
                'description' => "I have registered ".$user->first_name." ".$user->last_name." for ".$event->name." at ". $venue->name,
                'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
            ]);


            // Generate a new invoice for this ticket.
            if ($request->has('generate_invoice') && $request->generate_invoice){
                $this->ticketRepository->generateTicketItem($user, $pricing);

                $ticket->update(['invoice_id' => $invoice->id]);
                $ticket->save();

                // Set the extra's for this invoice.
                if (isset($eventData['selectedExtraId'])){
                    foreach ($eventData['selectedExtraId'] as $key=>$extraId){
                        $this->ticketRepository->setExtras($ticket, $invoice, $key);
                    }
                }

                // Set Dietary requirements for ticket.
                if ( (int)$eventData['dietary'] > 0 ) {
                    $this->ticketRepository->setDietary($invoice, $eventData['dietary'], $date);
                }

                $note->invoice()->associate($invoice);
                $ticket->invoice()->associate($invoice);
            }else{
                // Assign the webinar recordings to the user if any.
                if (count($pricing->recordings)){
                    foreach ($pricing->recordings as $recording){
                        auth()->user()->webinars()->save($recording->video);
                    }
                }
            }

            $user->notes()->save($note);
        // });
            } else {
                $not_registerd_events[] = "- ".$event->name;
            }
        }

        if ($request->has('generate_invoice') && $request->generate_invoice && count($successfull_tickets) > 0) {
            $this->ticketRepository->finaltouches($invoice);
            $this->dispatch((new SendEventTicketInvoiceJob($invoice))->delay(10));
        }
        $this->generateMessage($eventsData, $not_registerd_events);
        return response()->json(['success' => 'success']);
        
    }

    public function generateMessage($eventsData, $not_registerd_events) {
        $message = "User has been registered for Events Successfully. <br/>";
        if(count($not_registerd_events) > 0) {
            $message .= "User is already registered to attend this events:";

            if(count($eventsData) == count($not_registerd_events))
                $message = "User is already registered to attend this events:";

            $message = $message."<br/>".implode('<br/>', $not_registerd_events);
            Session::flash('error', $message);
        } else {
            Session::flash('success', $message);
        }
    }

    public function generateOrder(Request $request, $memberId)
    {
        $member = User::find($memberId);
        $event = $this->eventRepository->find($request['event_id']);

        if ($member->isRegisteredForEvent($event)) {
            alert()->error('Member is already registered to attend "' . $event->name . '".', 'Error')->persistent('Close');
            return redirect()->back();
        }

        DB::transaction(function () use($request, $memberId){

            // Member
            $member = User::find($memberId);

            //  Find Pricing
            $pricing = Pricing::find($request['pricing_id']);

            // Generate order
            $order = $this->generateEventOrder($member, $pricing, $request);

            $note = new Note([
                'type' => 'event_registration',
                'description' => "I have registered ".$member->first_name." ".$member->last_name." for ".$pricing->event->name." at ". $pricing->venue->name,
                'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
            ]);

            $note->save();
            $order->note()->associate($note);

            // Create Event Ticket
            $ticket = $this->createOrderTicket($request, $pricing, $order, $member);

            // Set Extras
            if (count($request->get('extras', [])) >= 1) {
                $this->setOrderExtras($ticket, $order, $request);
            }

            if ($request['dietary']) {
                $this->setOrderDietary($order, $request->dietary, $request->date_id);
            }

            $order->addItems($this->products);
            $order->autoUpdateAndSave();

            // Set Ticket Dates
            $this->setDates($request->only('date_id'), $ticket);

            if($order->total - $order->discount <= 0){
                $invoice = $this->invoiceOrderRepository->processCharge($order, 'Applied', 'Discount');
                if($order->fresh()->balance > 0){ 
                    $this->dispatch((new SendEventTicketOrderJob($order->fresh())));
                }
                $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
            }

            $this->dispatch((new SendEventConfirmation($ticket->user, $ticket->fresh())));
            if($order->fresh()->balance > 0){ 
                $this->dispatch((new SendEventTicketOrderJob($order->fresh())));
            }
            $ticket->delete();
            // Save the note to the account!
            $member->notes()->save($note);
        });

        alert()->success('Member has been successfully registered for the event!', 'Success!');
        return back();
    }

    public function generateCourseOrder(Request $request, $memberId)
    {
      
        $course = Course::find($request['course_id']);
        $member = User::find($memberId);

        if ($member->isCourseSubscribed($course)) {
            alert()->error('Member is already registered to attend "' . $course->name . '".', 'Error')->persistent('Close');
            return redirect()->back();
        }
        $discount  = 0;
        
        if($request->plan_type=='yearly'){

        
            $amount = $course->yearly_enrollment_fee;
            $discount = $course->annual_discount;
            
            if($course->type_of_course == 'semester'){
                if($request->course_type == 'partially')
                    {
                        $amount = $course->semester_price;
                    }elseif($request->course_type == 'full'){
                        $amount = ($course->semester_price)*($course->no_of_semesters);
                }
            }
        }else{
            $amount = $course->monthly_enrollment_fee;
            $discount = $course->discount;
        }

        DB::transaction(function () use($request, $memberId,$member,$course,$amount,$discount){

          
            //  Find Pricing
            //$pricing = Pricing::find($request['pricing_id']);

            // Generate order
            $order = $this->generateInvoice($member, $course, $request,$amount,$discount);
            
       
            $note = new Note([
                'type' => 'course_subscription',
                'description' => "I have Subscribed ".$member->first_name." ".$member->last_name." for Course ".$course->title,
                'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
            ]);

            $note->save();
            $order->note()->associate($note);


           

            $order->addItems($this->products);
            $order->autoUpdateAndSave();
            $member->courses()->save($course);
          
            if ($request->plan_type === 'yearly'){
                $plan = Plan::find($course->yearly_plan_id);
                $subscription = $member->newSubscription('course', $plan)->create();
            }else{
                $plan = Plan::find($course->monthly_plan_id);
                $subscription =   $member->newSubscription('course', $plan)->create();
            }
            $lastSubscrption = Subscription::whereIn('plan_id',[$course->yearly_plan_id,$course->monthly_plan_id])->where('name','course')->whereNotNull('student_number')->orderBy('id','desc')->first();
            $studentNumber = "";
            if($lastSubscrption){
                $arraydata = explode('_',$lastSubscrption->student_number);
                $numberOfCourseStudent = (int)$arraydata[sizeof($arraydata) - 1] + 1 ;
                $studentNumber = $course->id.'_'.date('y').'_'.date('m').'_';
                foreach(explode(' ',preg_replace('/[^a-zA-Z0-9_ -]/s','',$course->title)) as $title){
                    if($title != '' && ctype_alpha($title)){
                        $studentNumber .= strtoupper($title[0]);
                    }
                }
                $studentNumber .= '_'.$numberOfCourseStudent;
            }else{
                $studentNumber = $course->id.'_'.date('y').'_'.date('m').'_';
                foreach(explode(' ',preg_replace('/[^a-zA-Z0-9_ -]/s','',$course->title)) as $title){
                    if($title != '' && ctype_alpha($title)){
                        $studentNumber .= strtoupper($title[0]);
                    }
                }
                $studentNumber .= '_1';
                $course->update(['student_number' => $studentNumber]);
            }

            $now = Carbon::now();
            $subscription->student_number = $studentNumber ;
            $subscription->starts_at = $course->start_date;
            $subscription->agent_id = auth()->user()->id;
            $subscription->ends_at = $course->end_date;
            $subscription->no_of_debit_order = $course->no_of_debit_order;
            $subscription->completed_semester = 0;
            $subscription->course_type = $course->type_of_course;
            $subscription->completed_order = 0;
    
            if ($request->plan_type == 'yearly'){
                $endsat = Carbon::parse( date_format(Carbon::parse($now), 'D'). ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year)->addMonth(1)->addYear(1);
               
            }else{
                $endsat = Carbon::parse( date_format(Carbon::parse($now), 'D'). ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year)->addMonth(1);
            }
    
            $subscription->ends_at = $endsat;
            if($request->payment_method) {
                $subscription->payment_method = $request->payment_method;
            }
            $subscription->save();

            
           // $order->settle();
            if($order->total - $order->discount <= 0){
                $invoice = $this->invoiceOrderRepository->processCharge($order, 'Applied', 'Discount');
                //$this->dispatch((new SendEventTicketOrderJob($order->fresh())));
              //  $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
            }

           // $this->dispatch((new SendEventConfirmation($ticket->user, $ticket->fresh())));
            //$this->dispatch((new SendEventTicketOrderJob($order->fresh())));

            // Save the note to the account!
            $member->notes()->save($note);
        });

        alert()->success('Member has been successfully registered for the event!', 'Success!');
        return back();
    }

    public function postAssignToPlans(Requests\EventAssignToPlansRequest $request)
    {
        $event = Event::findOrFail($request->event_id);
        $venue = Venue::findOrFail($request->venue_id);
        $date = Date::findOrFail($request->date_id);
        $plans = Plan::whereIn('id', $request->plans_ids)->get();
        $pricing = Pricing::where('event_id', $event->id)->where('venue_id', $venue->id)->first();

        if (count($plans) < 1){
            $users = User::whereDoesntHave('subscriptions')->with(['tickets'])->get();

        }else{
            $users = User::whereHas('subscriptions', function ($query) use ($plans) {
                $query->whereIn('plan_id', $plans->pluck('id')->toArray());
            })->with(['tickets'])->get();
        }

        $bulkTickets = collect([]);
        $codes = collect([]);
        foreach ($users as $user) {
            $alreadyRegistered = false;
            $tickets = $user->tickets;
            foreach ($tickets as $ticket) {
                if ($ticket->event_id == $event->id) {
                    $alreadyRegistered = true;
                    break;
                }
            }
            if (! $alreadyRegistered) {
                $ticket = new Ticket;
                $code = str_random(20);
                $ticket->code = $code;
                $codes->push($code);
                $ticket->name = $pricing->name;
                $ticket->description = $pricing->description;
                $ticket->first_name = $user->first_name;
                $ticket->last_name = $user->last_name;
                $ticket->user_id = $user->id;
                $ticket->event_id = $event->id;
                $ticket->venue_id = $venue->id;
                $ticket->pricing_id = $pricing->id;
                $ticket->invoice_id = null;
                $ticket->dietary_requirement_id = $request->dietary;
                $ticket->email = $user->email;

                $bulkTickets->push($ticket);
            }
        }

        DB::transaction(function () use ($bulkTickets, $codes, $date) {

            foreach ($bulkTickets as $bulkTicket){
                DB::table('tickets')->insert([
                    "code" => $bulkTicket->code,
                    "name" => $bulkTicket->name,
                    "description" => $bulkTicket->description,
                    "first_name" => $bulkTicket->first_name,
                    "last_name" => $bulkTicket->last_name,
                    "user_id" => $bulkTicket->user_id,
                    "event_id" => $bulkTicket->event_id,
                    "venue_id" => $bulkTicket->venue_id,
                    "pricing_id" => $bulkTicket->pricing_id,
                    "invoice_id" => 0,
                    "dietary_requirement_id" => 0,
                    "email" => $bulkTicket->email,
                ]);
            }

            $tickets = Ticket::whereIn('code', $codes->toArray())->get();
            $bulkTicketsDates = $tickets->map(function ($ticket, $key) use ($date) {
                return ['ticket_id' => $ticket->id, 'date_id' => $date->id];
            });

            DB::table('date_ticket')->insert($bulkTicketsDates->toArray());
        });

        alert()->success('The event and venue has been assigned.', 'Success');
        return redirect()->route('admin.events.index');
    }

    public function getAttendees($eventId, $venueId)
    {
        $eventAttendees = Ticket::with(['user', 'pricing', 'invoice'])->where('event_id', $eventId)->where('venue_id', $venueId)->get();
        return $eventAttendees;
    }

    public function saveAttendees(Requests\SaveAttendeesRequest $request, $eventId, $venueId)
    {
        $attendees = collect($request->get('attendees', []));
        $attendeesIds = $attendees->pluck('id')->toArray();
        $tickets = Ticket::whereIn('id', $attendeesIds)->with(['invoice', 'event', 'venue', 'user', 'dates', 'pricing'])->get();

        if (count($tickets) != count($attendees))
            return response()->json('Different sized arrays.', 422);

        $attendees = $attendees->keyBy('id');

        $attendedBulk = collect([]);
        $unattendedBulk = collect([]);
        $invoiceCancellations = collect([]);
        $ticketDeletes = collect([]);
        $cpdInserts = collect([]);
        $certificateInserts = collect([]);
        $cpdDeletes = collect([]);

        foreach ($tickets as $ticket) {
            $attendee = $attendees->get($ticket->id);
            if (!$attendee)
                return response()->json('Found ticket with no attendee.', 422);

            //Update attendance
            if ($attendee['attended'] != $ticket->attended) {
                $attended = boolval($attendee['attended']);
                if ($attended) {
                    $attendedBulk->push(['id' => $ticket->id, 'attended' => boolval($attendee['attended'])]);
                    $dates = $ticket->dates;
                    foreach ($dates as $date) {
                        $newCPD = new Cpd([
                            'user_id' => $ticket->user->id,
                            'date' => $date->getOriginal('date'),
                            'hours' => $ticket->pricing->cpd_hours,
                            'source' => $ticket->event->name . ': ' . $ticket->venue->name,
                            //TODO remove after push
                            'has_certificate' => true,
                            //TODO remove after push
                            'ticket_id' => $ticket->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'verifiable' => true
                        ]);
                        $cpdInserts->push($newCPD);
                    }
                } else {
                    $unattendedBulk->push(['id' => $ticket->id, 'attended' => boolval($attendee['attended'])]);
                    $cpdDeletes->push(['user_id' => $ticket->user->id, 'ticket_id' => $ticket->id]);
                }
            }

            //Update invoice status
            if ($ticket->invoice && $attendee['invoice']['status'] != $ticket->invoice->status) {
                $invoiceCancellations->push(['id' => $attendee['invoice']['id'], 'status' => $attendee['invoice']['status']]);
            }

            //Marked for deletion and invoice cancellation
            if ($attendee['marked_for_deletion']) {
                $ticketDeletes->push($ticket->id);
            }
        }

        //Perform bulk actions
        if (count($attendedBulk) > 0) {
            Ticket::whereIn('id', $attendedBulk->pluck('id')->toArray())->update(['attended' => true]);
        }

        if (count($cpdInserts) > 0) {
            foreach ($cpdInserts->unique('user_id') as $cpdInsert) {
                $cpdInsert->save();
                $view_path = 'certificates.attendance';
                if($ticket->event->slug == "webinar-wills-and-intestate-succession-2021" || $ticket->event->slug == "trusts-beyond-2021" || $ticket->event->slug == "trusts-beyond-2021-important-short-and-long-term-risks-and-benefits-part-2" || $ticket->event->slug == "trusts-beyond-2021-risks-for-trustees-and-tax-practitioners-in-trust-administration-part-3" || $ticket->event->slug == "trusts-beyond-2021-specific-tax-risks-related-to-trusts-part-4")
                {
                    $view_path = 'certificates.competence';
                }
                $cpdInsert->certificate()->create([
                    'source_model' => Ticket::class,
                    'source_id' => $ticket->id,
                    'source_with' => ['dates', 'event', 'event.presenters'],
                    'view_path' => $view_path
                ]);
            }
        }

        if (count($unattendedBulk) > 0) {
            Ticket::whereIn('id', $unattendedBulk->pluck('id')->toArray())->update(['attended' => false]);
        }
        if (count($cpdDeletes) > 0) {
            Cpd::whereIn('user_id', $cpdDeletes->pluck('user_id')->toArray())->whereIn('ticket_id', $cpdDeletes->pluck('ticket_id')->toArray())->delete();
            Certificate::where('source_model', Ticket::class)->whereIn('source_id', $cpdDeletes->pluck('ticket_id')->toArray())->delete();
        }
        if (count($invoiceCancellations) > 0) {
            Invoice::whereIn('id', $invoiceCancellations->pluck('id')->toArray())->update(['status' => 'cancelled', 'balance' => 0]);
        }
        if (count($ticketDeletes) > 0) {
            Ticket::whereIn('id', $ticketDeletes->toArray())->delete();
        }

        return response()->json(['status' => 'success']);
    }
    public function generateInvoice($user, $course, $request, $amount,$discount=0)
    {
        $invoice = new InvoiceOrder;
        $invoice->discount = $discount;
        $invoice->type = 'course';
        $invoice->setUser($user);
        if($course->exclude_vat == 1){
            $invoice->vat_rate = 0;
        }
        $invoice->save();

        $item = new Item;
        $item->type = 'course';
        $item->name = $course->title;
        $item->description = 'Online Course Access';
        $item->price = $amount;
        $item->discount = $discount;
        $item->item_id = $course->id;
        $item->item_type = get_class($course);
        $item->course_type = $request->plan_type;
        $item->save();

        $this->products[] = $item;

        // Create Course Invoice Entry...
        // DBTable::table('course_invoice')->insert([
        //     'course_id' => $course->id,
        //     'invoice_id' => $invoice->id
        // ]);

        return $invoice;
    }
    public function generateEventOrder($member, $pricing, $request){
        $order = new InvoiceOrder;
        $order->type = 'event';
        $order->setUser($member);
        $order->save();
        $item = new Item;
        $item->type = 'ticket';
        $item->name = $pricing->event->name;
        $item->description = $pricing->description . ' - ' . $pricing->venue->name;
        $item->price = $pricing->price;
        $item->discount = ($pricing->getDiscountForUser($member) + $pricing->getPromoCodesDiscount() + $pricing->getCPDSubscriberDiscount($member));
        $item->item_id = $pricing->event->id;
        $item->item_type = get_class($pricing->event);
        $item->save();

        $this->products[] = $item;
        return $order;
    }

    public function createOrderTicket($request, $pricing, $order, $member)
    {
        $ticket = new Ticket;
        $user = $member;

        $ticket->code = str_random(20);
        $ticket->name = $pricing->name;
        $ticket->description = $pricing->description;
        $ticket->first_name = $user->first_name;
        $ticket->last_name = $user->last_name;
        $ticket->user_id = $user->id;
        $ticket->event_id = $pricing->event->id;
        $ticket->venue_id = $pricing->venue->id;
        $ticket->pricing_id = $pricing->id;
        $ticket->invoice_order_id = $order->id;
        $ticket->dietary_requirement_id = ($request->dietary ? $request->dietary : '0');
        $ticket->email = $user->email;
        $ticket->save();
        return $ticket;
    }

    public function setOrderExtras($ticket, $order, $request)
    {
        foreach ($request->extras as $extra) {
            $toAdd = Extra::find($extra);
            if ($toAdd->price > 0)
                $this->addExtraToInvoiceOrder($toAdd, $order, $ticket->event->name);

            $ticket->extras()->attach($toAdd);
        }
    }

    public function addExtraToInvoiceOrder($extra, $order, $event_name)
    {
        $item = new Item();
        $item->type = 'product';
        $item->name = $extra->name;
        $item->description = $event_name;
        $item->price = $extra->price;
        $item->item_id = $extra->id;
        $item->item_type = get_class($extra);
        $item->save();

        $this->products[] = $item;
    }

    public function setOrderDietary($order, $dietary, $dates)
    {
        $multiplier = count($dates);
        $toAdd = DietaryRequirement::find($dietary);
        if ($toAdd->price > 0) {
            for ($i=1; $i <= $multiplier; $i++) {
                $this->addDietaryToInvoiceOrder($toAdd, $order);
            }
        }
    }

    public function addDietaryToInvoiceOrder($dietary, $order)
    {
        $item = new Item();
        $item->type = 'product';
        $item->name = $dietary->name;
        $item->description = 'Dietary Requirement';
        $item->price = $dietary->price;
        $item->item_id = $dietary->id;
        $item->item_type = get_class($dietary);
        $item->save();

        $this->products[] = $item;
    }

    public function setDates($dates, $ticket)
    {
        foreach ($dates as $date) {
            $toAdd = Date::find($date);
            $ticket->dates()->attach($toAdd);
        }
    }

}
