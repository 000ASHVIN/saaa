<?php

namespace App\Http\Controllers\Dashboard;

use App\AppEvents\Event;
use App\AppEvents\PromoCode;
use App\Billing\Invoice;
use App\Body;
use App\Certificate;
use App\Charge;
use App\DownloadWebinars;
use App\FaqTag;
use App\Http\Requests\UpdateContactDetailsRequest;
use App\Http\Requests\UpdateNPORegistrationNumberRequest;
use App\Jobs\SendEmailAddressApprovalEmailRequest;
use App\Jobs\SendIdNumberApprovalEmailRequest;
use App\Jobs\SendMembershipConfirmation;
use App\Recording;
use App\Repositories\DebitOrder\DebitOrderRepository;
use App\Store\Order;
use App\Presenters\Presenter;
use App\Blog\Category;

use App\Thread;
use App\UploadOrReplaceImage;
use App\Users\Cpd;
use App\Users\User;
use App\AppEvents\Ticket;
use App\Videos\Video;
use Carbon\Carbon;
use App\Http\Requests;
use App\Subscriptions\Models\Plan;
use DaveJamesMiller\Breadcrumbs\Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Users\UserRepository;
use App\AppEvents\EventRepository;
use App\Assessment;
use App\Subscriptions\Subscription;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Input;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Readers\Html;
use App\Support\Collection;
use App\Rep;
use App\PracticePlanTabs;
use App\SponsorList;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\InvoiceOrder;
use App\Assessments\Attempt;
use App\Repositories\Sendinblue\SendingblueRepository;

use Auth;
use Hash;
use Illuminate\Support\Facades\Cookie;
use App\Jobs\SendOTP;
use App\OTP;
use App\NumberValidator;
use App\Repositories\SmsRepository\SmsRepository;
use App\Users\Industry;

class DashboardController extends Controller
{
    protected $eventRepository;
    protected $userRepository;
    protected $numvalidator;
    protected $smsRepo;
    private $debitOrderRepository;

    public function __construct(EventRepository $eventRepository, UserRepository $userRepository, DebitOrderRepository $debitOrderRepository,NumberValidator $numvalidator,SmsRepository $smsRepo)
    {
        $contactDetails = ['postUpdateContactDetails'];
        $edit_post = ['getEdit', 'postEdit'];
        $billing = ['billing_information', 'update_billing_information'];
        $overdueInvoices = ['getOverdueInvoices', 'getInvoices', 'getAccountStatement'];

        $this->middleware('auth', ['except' => 'debit_order_setup']);
       // $this->middleware('id-number-check', ['except' => $billing, $edit_post, $overdueInvoices, $contactDetails]);
        $this->middleware('CheckSuspendedStatus', ['except' => $billing, $edit_post, $overdueInvoices, $contactDetails ]);
        $this->middleware('CheckProfessionalBody', ['except' => array_merge ($edit_post , $billing)]);
        $this->middleware('CheckPaymentMethod', ['except' => $billing, $edit_post, $overdueInvoices]);
        $this->middleware('checkDebitOrderDetails', ['except' => $billing, $edit_post, $overdueInvoices, $contactDetails]);

        $this->eventRepository = $eventRepository;
        $this->numvalidator = $numvalidator;
        $this->smsRepo = $smsRepo;
        $this->userRepository = $userRepository;
        $this->debitOrderRepository = $debitOrderRepository;
        if(auth()->user() && !auth()->user()->profile)
        {
            auth()->user()->profile()->create([]);
        }
    }

    public function show()
    {
        $user = $this->userRepository->find(auth()->user()->id);
        $promptForContactDetails = $user->requireContactDetails() && !$user->hasCell();
        $tickets = auth()->user()->tickets()->with(['event', 'dates'])->get()->reverse();
        $newTickets = collect();
        foreach($tickets as $ticket)
        {
            
            if(! $ticket->event) {
                $ticket->years = $ticket->created_at->format('Y');
            }
            if(! $ticket->dates->first()) {
                $ticket->years  =  $ticket->created_at->format('Y');
            }
            if($ticket->dates->count()){
                $ticket->years =  Carbon::parse($ticket->dates->first()->date)->format('Y');
            }
            $newTickets->push($ticket);
        }
        $tickets= $newTickets;

        // Check if current year exists or not
        $grouped = $tickets->groupBy('years');
        $currentYear = Carbon::now()->format('Y');
        $showCurrentYear = isset($grouped[$currentYear])?false:true;

        return view('dashboard.show', compact('user', 'tickets', 'promptForContactDetails', 'showCurrentYear', 'currentYear'));
    }

    public function getEdit()
    {
        $user = $this->userRepository->find(auth()->user()->id);
        $cell = $user->cell;
        $bodies = Body::all();
        $interest = config('signup.interest');
        $employment = config('signup.employment');
        // $industry = config('signup.industry');
        $industry = Industry::all()->pluck('title', 'id')->toArray();

        $user_interest = json_decode($user->interest);
        $user_industry = $user->industry;
        if(substr($user_industry, 0, 5) == 'Other') {
            $user->industry = 'Other';
            $user->other_industry = substr($user_industry, 7);
        }
        return view('dashboard.edit.edit', compact('user', 'bodies','cell', 'interest', 'employment','industry', 'user_interest'));
    }

    public function postEdit(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        $this->setProfessionalBody($request);
        $cell = $request->full_number;
    
        $user->update($request->except('_token', 'email', 'interest'));
        $user->updateProfile($request);
        $user->cell = $cell;
        $user->interest = json_encode($request->interest);
        if($request->industry == 'Other') {
            $user->industry = $request->industry.': '.$request->other_industry;
        }
        $user->save();

        $this->calculatePercentage($user);
        $user->settings()->merge($request['settings']);

        $user->saveAdditionalProfessionalBodies($request['additional_professional_bodies']);

        alert()->success("Your profile has been updated!", 'Success');
        return back();
    }

    public function getTickets(Request $request)
    {
        $user=auth()->user();
        $tickets = $user->threads()->orderBy('id', 'desc');

        // Search 
        if($request->title) {
            $tickets->where('threads.title','LIKE','%'.$request->title.'%');
        }
        if($request->status) {
            $tickets->where('threads.status','=',$request->status);
        }
        if($request->category) {
            $tickets->where('threads.category','=',$request->category);
        }
        $tickets=$tickets->paginate(10);

        $categories = Category::select('categories.*')
            ->join('threads','categories.id','=','threads.category')
            ->where('threads.user_id','=',$user->id)
            ->groupBy('categories.id')
            ->get();
        $statuses = getThreadStatuses();
        
        return view('dashboard.tickets.tickets', compact('tickets', 'statuses', 'categories'));
    }

    public function getInvoices()
    {
        $user = $this->userRepository->find(auth()->user()->id);
        $invoices = $user->invoices()->paginate(15);

        return view('dashboard.invoices', compact('user', 'invoices'));
    }

    public function getInvoiceOrders()
    {
        $user = $this->userRepository->find(auth()->user()->id);
        $orders = $user->InvoiceOrders()->orderBy('id','desc')->paginate(15);
        return view('dashboard.orders', compact('user', 'orders'));
    }

    public function getOverdueInvoices()
    {
        if(auth()->user()->subscription)
        {
            $installments = auth()->user()->subscription->installments()->whereHas('invoice', function ($query) {
                return $query->where('status', 'unpaid');
            })->get();

            $invoices = collect([]);

            foreach ($installments as $installment) {
                $invoices->push($installment->invoice);
            }
            
            alert()->warning('You have unpaid invoices that are due, please settle them before proceeding.', 'Overdue invoices')->persistent();
            return view('dashboard.overdue-invoices', compact('invoices'));
        }
    }

    public function getCPD()
    {
        return view('dashboard.cpd');
    }

    public function getEvents(Request $request)
    {
        $InvoiceOrder = InvoiceOrder::whereHas('items', function ($query) {
            $query->where('item_type','=','App\AppEvents\Event');
        })->where('user_id',auth()->user()->id)->where('type','event')->where('status','unpaid')->where('paid',0)->get();
        $InvoiceOrderPaid = InvoiceOrder::whereHas('items', function ($query) {
            $query->where('item_type','=','App\AppEvents\Event');
        })->with('items')->where('user_id',auth()->user()->id)->where('type','event')->where('status','paid')->where('paid',1)->get();

        $InvoicePaid = Invoice::whereHas('items', function ($query) {
            $query->where('item_type','=','App\AppEvents\Event');
        })->with('items')->where('user_id',auth()->user()->id)->where('type','event')->where('status','paid')->where('paid',1)->get();
        $eventInvoicePaid = $InvoicePaid->pluck('items')->collapse()->pluck('item_id')->toArray();
        $eventPaid = $InvoiceOrderPaid->pluck('items')->collapse()->pluck('item_id')->toArray();
        $paidEvents = array_merge($eventInvoicePaid,$eventPaid);

        $items = collect();
        $currentPlan = auth()->user()->subscription('cpd')->plan;
        $events = $currentPlan->getPlanEvents();
        $currentPlanEvents = array_merge($events->pluck('id')->toArray(),$paidEvents);
        foreach($InvoiceOrder as $order)
        {
            foreach($order->items as $item)
            {
                if($item->item_type == get_class(new Event()) && !in_array($item->item_id,$currentPlanEvents)){
                    $items->push($item->item_id);
                }
            }
        }
        $eventpending=$items->toArray();
        // Get search filters
        $filters = [
            'title'=>$request->title,
            'presenter'=>$request->presenter,
            'categories'=>$request->categories,
            'subscription'=>$request->subscription,
            'year'=>$request->event_year
        ];

        $filterMyEvents = [];
        $filterUpcoming = [];

        if($request->input('type') != 'upcoming_events') {
            $filterUpcoming['subscription'] = 'yes';
        }

        if($request->input('type')=='my_events') {
            $filterMyEvents = $filters;
        }
        else if($request->input('type')=='upcoming_events') {
            $filterUpcoming = $filters;
            switch($request->upcoming_filter) {
                case 'paid':
                    $filterUpcoming['paid'] = true;
                    break;
                case 'free':
                    $filterUpcoming['paid'] = false;
                    break;
                default:
                    $filterUpcoming['subscription'] = 'yes';
                    break;
                
            }
        }

        // My events pagination

        // Event Year search filter 
        $event_years = [];
        $filtered = array_filter($filters);
        if($request->input('type')) {
            $current_year = '';
        } else {
            $current_year = Carbon::now()->format('Y');
        }
        $years = Event::selectRaw('DISTINCT(YEAR(start_date)) as event_year')->orderBy('event_year','desc')->get();
        $event_years = $years->pluck('event_year')->toArray();
        $event_years = array_combine($event_years, $event_years);

        $purchased_event_ids = auth()->user()->tickets->pluck('event_id')->toArray();
        // $myEvents = Event::whereIn('events.id',$purchased_event_ids)->get();

        $myTickets = Ticket::whereRaw(1);
        $myTickets->where('user_id', '=', auth()->user()->id)
                    ->with(['event', 'event.links', 'event.presenters','event.categories']);

        // Filters on events
        if(isset($filters['title']) && !empty($filters['title'])) {
            $myTickets->search($filters['title'],null,true);
        }
        if(isset($filters['presenter']) && !empty($filters['presenter'])) {
            $myTickets->whereHas('event.presenters', function($q) use($filters) {
                $q->where('id', '=', $filters['presenter']);
            });
        }
        if(isset($filters['categories']) && !empty($filters['categories'])) {
            $myTickets->whereHas('event.categories', function($q) use($filters) {
                $q->where('categories.id', '=', $filters['categories']);
            });
        }

        if(isset($filters['year']) && !empty($filters['year'])) {
            $myTickets->whereHas('event', function($q) use($filters) {
                $q->whereYear('events.start_date','=',$filters['year']);
            });
        }
        if(!empty($eventpending)){
            $myTickets = $myTickets->whereNotIn('event_id',$eventpending);
        }
        $past_events = clone $myTickets;
        
        $past_events->select('tickets.*')->join('events','events.id','=','tickets.event_id')->join('dates','dates.venue_id','=','tickets.venue_id')->join('venues','venues.id','=','tickets.venue_id')->where('venues.is_active',true)->where('dates.is_active',true)->where('dates.is_active',true);
        $past_events->whereHas('event', function($q) {
            $q->where('dates.date', '<', Carbon::today()->format('Y-m-d'));
        });

        if($request->completed_filter) {
            $past_events = $past_events->where('event_complete', $request->completed_filter);
        }
       
        $past_events = $past_events->groupBy('events.id');
        if(!$filters['title']) {
            $past_events->orderBy('events.start_date','desc');
        }
        else {
            $past_events->orderByRaw('relevance desc, events.start_date desc');
        }
        

        $past_events = $past_events->paginate(5, ['*'], 'past');
        $past_events->appends(Input::except('my','upcoming','new_view','up','past'));

        $my_upcoming_events = clone $myTickets;
        $my_upcoming_events->select('tickets.*',DB::raw('events.start_date as starts'))->join('events','events.id','=','tickets.event_id')->join('dates','dates.venue_id','=','tickets.venue_id')->join('venues','venues.id','=','tickets.venue_id')->where('venues.is_active',true)->where('dates.is_active',true);

        if(!$filters['title']) {
            $my_upcoming_events->orderBy('events.start_date','asc');
        }
        else {
            $my_upcoming_events->orderByRaw('relevance desc, events.start_date asc');
        }

        $my_upcoming_events->whereHas('event', function($q) {
            $q->where('dates.date', '>=', Carbon::today()->format('Y-m-d'));
        });
        $my_upcoming_events = $my_upcoming_events->groupBy('events.id');
        $my_upcoming_events = $my_upcoming_events->get();
        $currentPlan = auth()->user()->subscription('cpd')->plan;
        $events = $currentPlan->getPlanEvents();

        $filtered = $events->filter(function ($event) {
            if ($event->end_date >= Carbon::now()) {
                return $event;
            }
        });
        if($filtered->count()){
            $filtered = $filtered->unique('id');
            $filtered = $filtered->values()->all();
        }
        foreach($filtered as $event){
            $newEvent =New Ticket();
            $event->links;
            $event->presenters;
            $event->categories;
            $newEvent->event = $event;
            $newEvent->event_id= $event->id;
            $newEvent->starts = $event->start_date;
            $newEvent->venue = $event->venues->first();
            $my_upcoming_events->push($newEvent);
        }
        $my_upcoming_events = $my_upcoming_events->unique('event_id');
        $page = isset($_GET['page'])?$_GET['page']:1;
        $my_upcoming_events = $my_upcoming_events->sortBy('starts');
        $my_upcoming_events = new LengthAwarePaginator(
            $my_upcoming_events->forPage($page, 5),
            $my_upcoming_events->count(),
            5,
            $page,
            ['path' => url(request()->getPathInfo())]
        );

        $my_upcoming_events->appends(Input::except('my','upcoming','new_view','up','past'));
        

        // Upcoming events pagination

        $upcomingEvents = $this->eventRepository->getUpcomingFilterEvents($filterUpcoming);
        $upcomingEvents->appends(Input::except('my','upcoming','new_view'));

        $currentPlan = auth()->user()->subscription('cpd')->plan;
        $events = $currentPlan->getPlanEvents();
        $currentPlanEvents = $events->pluck('id');

        //Search filter dropdown values
        $presenters = Presenter::orderBy('name')->get()->pluck('name', 'id' );
        $webinar_categories = Category::where('parent_id',0)->get();
        $categories = Category::where('parent_id',0)->orderBy('title')->get()->pluck('title', 'id');

        //Active tab for pagination & search filter
        $activeTab = 'my_events';
        $eventsActiveTab = 'my_upcoming_events';
        if($request->input('event')== 'past_events') {
            $eventsActiveTab = 'past_events';
        }
        if($request->past) {
            $eventsActiveTab = 'past_events';
        }
        if($request->input('type')== 'upcoming_events') {
            $activeTab = 'upcoming_events';
        }
        if($request->upcoming) {
            $activeTab = 'upcoming_events';
        }

        return view('dashboard.events', compact('myTickets','currentPlanEvents','upcomingEvents','presenters','categories','webinar_categories', 'videos', 'activeTab','eventsActiveTab', 'request','sub_category', 'title', 'category', 'cpd','event_years', 'current_year', 'purchased_event_ids','my_upcoming_events','past_events'));
    }

    /*
    public function getPlanEvents($plan)
    {
        $events = collect();

        $plan->features->each(function ($feature) use($events){
            if (count($feature->pricings)){
                $feature->pricings->each(function ($pricing) use ($events){
                    if ($pricing->event){
                        $events->push($pricing->event);
                    }
                });
            }
        });

        $filtered = $events->filter(function ($event) {
            if (count($event->venues) > 0 && $event->venues->where('type','online')->count()) {
                return $event;
            } elseif ($event->end_date <= Carbon::now()) {
                return $event;
            }
        });

        return $filtered;
    }
    */

    public function getarticles()
    {
        $articles = auth()->user()->posts()->paginate(10);;
        return view('dashboard.articles', compact('articles'));
    }

    public function getTicketLinksAndResources($ticketId)
    {
        $ticket = Ticket::with(['event', 'user', 'event.links', 'event.files', 'event.assessments', 'invoice', 'venue', 'dates', 'pricing', 'pricing.webinars', 'pricing.recordings', 'pricing.recordings.video', 'pricing.recordings.video.videoProvider', 'extras', 'dietaryRequirement'])->findOrFail($ticketId);

        $user = auth()->user();
        if($user->force_suspend || $user->status == 'suspended') {
            $subscription = $user->subscription('cpd');
            if($subscription) {
                $events = $subscription->plan->getPlanEventsAll($user);
                if(count($events)) {
                    $isExists = $events->where('id', $ticket->event_id)->first();
                    if($isExists) {
                        alert()->error('Your Account is not active, please activate your account to access this event', 'Error!');
                        return redirect()->route('dashboard.events');
                    }
                }
            }
        }

        $hasCPD = $ticket->pricing->cpd_hours > 0;
        $cpd = Cpd::where('user_id', $ticket->user->id)->where('ticket_id', $ticket->id)->where('source','NOT LIKE','%Assessment:%')->first();
        $claimedCPD = count($cpd) > 0;
        $ticket->event->getWebinar(auth()->user());
        $links = $ticket->event->links->groupBy(function($item)
        {
            return $item->created_at->format('F-Y');
        });

        $user_id = $ticket->user->id;
        $assessments = $ticket->event->assessments;
        $attempts = [];
        $allAttempts = [];
        foreach($assessments as $assessment) {
            $result = Attempt::where('user_id', $user_id)->where('assessment_id', $assessment->id)->where('passed', true)->get();
            if(count($result) > 0) {
                $attempts[] = $result;
            }
            $allAttempts[$assessment->id] = Attempt::where('user_id', $user_id)->where('assessment_id', $assessment->id)->get();
        }

        $canManuallyClaimCPD = $ticket->pricing->can_manually_claim_cpd;
       
        $assessmentCPD = null;
        if(count($attempts) > 0)
        {
            $cpdIds = Cpd::where('user_id', $user_id)->where('source', 'like', '%'.$assessments->first()->title)->get()->pluck('id')->toArray();
            $certificate = Certificate::where('source_model', get_class(new Assessment()))->where('source_id', $assessment->id)->whereIn('cpd_id', $cpdIds)->orderBy('created_at', 'desc')->first();
            if($certificate) {
                $assessmentCPD = Cpd::find($certificate->cpd_id);
            }
        }
       

        return view('dashboard.event-links-and-resources', compact('links','ticket', 'hasCPD', 'claimedCPD', 'canManuallyClaimCPD', 'cpd', 'attempts', 'allAttempts', 'assessmentCPD'));
    }

    public function getVideoLinksAndResources($videoSlug)
    {
        $user = auth()->user();
        $video = \App\Video::where('slug', '=', $videoSlug)->first();
        
        $hasCPD = $video->hours > 0;
        $cpd = Cpd::where('user_id', $user->id)->where('video_id', $video->id)->first();
        $claimedCPD = ($cpd)?true:false;

        $links = $video->links->groupBy(function($item) {
            return $item->created_at->format('F-Y');
        });

        return view('dashboard.video-links-and-resources', compact('video', 'cpd', 'hasCPD', 'claimedCPD', 'links'));
    }

    public function claimCPD($ticketId)
    {
        $ticket = Ticket::with(['user', 'dates', 'pricing', 'event', 'venue'])->findOrFail($ticketId);
        $existing = Cpd::where('user_id', $ticket->user->id)->where('ticket_id', $ticket->id)->where('source','NOT LIKE','%Assessment:%')->count() > 0;
        $view_path = 'certificates.attendance';
        
        if($ticket->event->slug == "webinar-wills-and-intestate-succession-2021" || $ticket->event->slug == "trusts-beyond-2021" || $ticket->event->slug == "trusts-beyond-2021-important-short-and-long-term-risks-and-benefits-part-2" || $ticket->event->slug == "trusts-beyond-2021-risks-for-trustees-and-tax-practitioners-in-trust-administration-part-3" || $ticket->event->slug == "trusts-beyond-2021-specific-tax-risks-related-to-trusts-part-4")
        {
            $view_path = 'certificates.competence';
        }
        if ($ticket->verifiable_cpd === 1 || $ticket->verifiable_cpd === null){
            if ($existing) {
                alert()->error('You have already claimed the CPD for that ticket.', 'Error');
                return redirect()->back();
            }

            $dates = $ticket->dates;
            foreach ($dates as $date) {
                $cpd = new Cpd([
                    'user_id' => $ticket->user->id,
                    'date' => $date->getOriginal('date'),
                    'hours' => $ticket->pricing->cpd_hours,
                    'source' => $ticket->event->name . ': ' . $ticket->venue->name,
                    'has_certificate' => $ticket->pricing->attendance_certificate,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'verifiable' => $ticket->pricing->cpd_verifiable,
                ]);

                $cpd->ticket()->associate($ticket);
                $cpd->save();

                $cpd->certificate()->create([
                    'source_model' => Ticket::class,
                    'source_id' => $ticket->id,
                    'source_with' => ['dates', 'event', 'event.presenters'],
                    'view_path' => $view_path
                ]);
            }
        }else{
            alert()->error('You cannot claim CPD for this free event', 'Error');
            return redirect()->back();
        }

        $ticket->attended = true;
        $ticket->save();

        alert()->success('The CPD has been added to your profile', 'Success');
        return redirect()->route('dashboard.cpd');
    }

    public function claimVideoCPD($video_id) {

        $user = auth()->user();
        $video = \App\Video::findOrFail($video_id);
        $existing = Cpd::where('user_id', $user->id)->where('video_id', $video->id)->count() > 0;
        $user_webinar = $user->webinars()->where('videos.id', $video_id)->first();
        
        if ($user_webinar){
            if ($existing) {
                alert()->error('You have already claimed the CPD for that ticket.', 'Error');
                return redirect()->back();
            }

            $cpd = new Cpd([
                'user_id' => $user->id,
                'date' => Carbon::now(),
                'hours' => $video->hours,
                'source' => 'Webinars On Demand' . ': ' . $video->title,
                'has_certificate' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'verifiable' => 0,
                'video_id' => $video->id,
                'cpd_type' => 'wod'
            ]);
            $cpd->save();

            $cpd->certificate()->create([
                'source_model' => \App\Video::class,
                'source_id' => $video->id,
                'source_with' => [],
                'view_path' => 'certificates.wob'
            ]);
        }else{
            alert()->error('You cannot claim CPD for this video.', 'Error');
            return redirect()->back();
        }

        alert()->success('The CPD has been added to your profile', 'Success');
        return redirect()->route('dashboard.cpd');

    }

    public function getProducts()
    {
        $hasPendingOrder = Order::hasPendingOrder(auth()->user());
        $orders = Order::getAllGrouped(auth()->user());
        return view('dashboard.products', compact('orders', 'hasPendingOrder'));
    }

    public function getAvatar()
    {
        $user = $this->userRepository->find(auth()->user()->id);
        return view('dashboard.edit.avatar', compact('user'));
    }

    public function StoreAvatar(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'mimes:jpeg,bmp,png'
        ]);

        UploadOrReplaceImage::UploadOrReplace('profiles', 'avatar', auth()->user());
        return redirect()->back();
    }

    public function getPassword()
    {
        return view('dashboard.edit.password');
    }

    public function getCell(){
        return view('dashboard.edit.cell');
    }

    public function getOtpView(Request $request){
        // return strlen($request->cell);
        $validate = \Validator::make($request->all(), [
            'cell' => 'required|numeric'
        ]);
        if($validate->fails()){
            return response()->json(false);
        }
        if((strlen($request->cell) < 10) || (strlen($request->cell) > 15)) {
            return response()->json(false);
        }
        session(['temp_number' => $request->cell]);
        Cookie::queue(Cookie::forget('verify_cell'));
        Cookie::queue('verify_cell_form', 'yes', 60*24);
        $this->otp($request);
        if($request->ajax()){
            return response()->json(true);
        }
        return redirect()->back();
    }

    public function postOtpVerification(Request $request){
        
        $otps = OTP::where('user_id', auth()->user()->id)->get();
        $valid = $otps->where('otp', $request->code)->last();
        
        if($valid){
            $user = Auth::user();
            $updated = $user->update([
                'cell' => session('temp_number'),
                'is_cell_verified' => 1,
                'updated_at' => Carbon::now()
            ]);
            Cookie::queue(Cookie::forget('verify_cell'));
            Cookie::queue(Cookie::forget('verify_cell_form'));
            return response()->json(true);
            // alert()->success('Your Cell is verified', 'Success');
            // return redirect()->back();
        }
        Cookie::queue(Cookie::forget('verify_cell_form'));
        return response()->json(false);
    }

    public function otp(Request $request)
    {
        $otp = rand(1000, 9999);
        $app_name = config('app.name');

        $message = "The Tax Faculty - Enter OTP {$otp} to verify your mobile number on your online profile. Queries? Contact +27129437002";
        // $number = $this->numvalidator->format($request->cell);
        $number = $request->cell;

        $this->smsRepo->sendSms([
            'message' => $message,
            'number' => $number
        ], $request->user());

        // Send an email with OTP
        // $this->dispatch((new SendOTP($request->user(), $otp)));

        // Save OTP to DB
        OTP::create([
            'otp' => $otp,
            'user_id' => $request->user()->id,
            'number' => $number,
        ]);

        return response()->json(['otp' => $otp]);
    }

    public function postPassword(Requests\ChangePasswordRequest $request)
    {
        $user = auth()->user();
        if (\Hash::check($request->current_password, $user->getAuthPassword())) {
            $user->password = $request->new_password;
            $user->save();
            alert()->success('Your password has been changed', 'Success');
            return redirect()->back();
        }
        alert()->error('Your current password is incorrect', 'Error');
        return redirect()->back()->withInput();
    }

    public function getPrivacy()
    {
        return view('dashboard.edit.privacy');
    }

    public function billing_information()
    {
        return view('dashboard.edit.billing_information');
    }

    public function update_billing_information(Request $request)
    {
        $user = auth()->user();

        if ($request->has(['bank', 'number'])){
            if ($user->debit == null){
                $this->debitOrderRepository->createDebitOrder($request, $user);
            }else{
                $this->debitOrderRepository->updateDebitOrder($request, $user);
                $this->debitOrderRepository->sendUpdatedBillingInformationEmail($user);
            }
        }

        return response()->json(['message' => 'success'], 200);
    }

    public function getSubscription()
    {
        return view('dashboard.edit.subscriptions');
    }

    public function cancelSub(Request $request, $id)
    {
        // Get the User
        $user = User::findorFail($id);

        // Get the User Current Plan
        $subscription = Subscription::where('user_id', $user->id)->first();

        // Update the Current User Plan
        $subscription->update([
            'plan_id' => Plan::where('price', 0)->first()->id,
            'installments_interval' => null,
            'has_installments' => null,
            'installments_total_number' => null,
            'installments_next_due_date' => null,
            'installments_grace_period_days' => null,
            'installments_item_id' => null,
            'is_overdue' => null
        ]);

        // Get all the user subscription Invoices
        $invocies = Invoice::where('user_id', $user->id)->get();

        // Cancel all his subscription invoices with a credit note.
        foreach ($invocies as $invoice){
            $invoice->transactions()->create([
                'user_id' => $invoice->user->id,
                'invoice_id' => $invoice->id,
            ]);
        }

//        $charge = Charge::where('user_id', $user->id)->first();
        $sub = Subscription::where('user_id', $user->id)->first();

//        $this->migrateToFreePlan($sub);

        alert()->success('Your Subscription has been cancelled, you are now on a free membership', 'Success')->persistent('Close');
        return redirect()->back();

//        if ($sub->plan->interval == 'monthly') {
//            if ($sub->created_at->addMonth(12) <= Carbon::now()) {
//                $charge = Charge::where('user_id', $user->id)->first();
//
//                $this->cancelRecurringBilling($charge->gateway_reference);
//                $this->migrateToFreePlan($sub);
//
//                alert()->success('Your Subscription has been cancelled, you are now on a free membership', 'Success')->persistent('Close');
//                return redirect()->back();
//            } else {
//                alert()->error('Your subscription can only be cancelled on ' . $sub->created_at->addMonth(12)->toFormattedDateString(), 'Error')->persistent('Close');
//                return redirect()->back();
//            }
//        } else {
////            $this->cancelRecurringBilling($charge->gateway_reference);
//            $this->migrateToFreePlan($sub);
//
//            alert()->success('Your Subscription has been cancelled, you are now on a free membership', 'Success')->persistent('Close');
//            return redirect()->back();
//        }
    }

    /**
     * @param $user
     * @return float|int
     */
    public function calculatePercentage($user)
    {
        $maximumPoints = 100;

        $completedFirstName = 0;
        if ($user->fresh()->first_name != '') {
            $completedFirstName = 10;
        }

        $completedLastName = 0;
        if ($user->fresh()->last_name != '') {
            $completedLastName = 6;
        }

        $completedEmail = 0;
        if ($user->fresh()->email != '') {
            $completedEmail = 10;
        }

        $completedIdNumber = 0;
        if ($user->fresh()->id_number != '') {
            $completedIdNumber = 10;
        }

        $completedCell = 0;
        if ($user->fresh()->cell != '') {
            $completedCell = 10;
        }

        $completedAlternativeCell = 0;
        if ($user->fresh()->alternative_cell != '') {
            $completedAlternativeCell = 6;
        }

        $completedProfessionalBody = 0;
        if (count($user->fresh()->body_id != null)) {
            $completedProfessionalBody = 6;
        }

        $completedMemberShipNumber = 0;
        if ($user->fresh()->membership_number != '') {
            $completedMemberShipNumber = 6;
        }

        $completedOcupation = 0;
        if ($user->fresh()->profile->position != '') {
            $completedOcupation = 10;
        }

        $completedCompany = 0;
        if ($user->fresh()->profile->company != '') {
            $completedCompany = 6;
        }

        $completedArea = 0;
        if ($user->fresh()->profile->area != '') {
            $completedArea = 10;
        }

        $completedProvince = 0;
        if ($user->fresh()->profile->province != '') {
            $completedProvince = 10;
        }

        $percentage = ($completedFirstName
                + $completedLastName
                + $completedEmail
                + $completedCell
                + $completedIdNumber
                + $completedAlternativeCell
                + $completedProfessionalBody
                + $completedMemberShipNumber
                + $completedOcupation
                + $completedCompany
                + $completedArea
                + $completedProvince
            ) * $maximumPoints / 100;

        $user->update(['completed' => $percentage]);
        return $percentage;
    }

    /**
     * @param ProfileUpdateRequest $request
     */
    public function setProfessionalBody(ProfileUpdateRequest $request)
    {
        if ($request['body_id'] != 'null') {
            if ($request['body_id'] != auth()->user()->body_id) {
                auth()->user()->update([
                    'body_id' => $request['body_id'],
                    'membership_number' => $request['membership_number'],
                    'membership_verified' => false,
                ]);
                $this->sendConfirmationEmailToProfessionalBody(auth()->user());
            }
        } else {
            auth()->user()->update([
                'body_id' => null,
                'membership_number' => null,
                'membership_verified' => false,
            ]);
        }
    }

    protected function cancelRecurringBilling($reference)
    {
        \Twocheckout::username('SuperAdmin');
        \Twocheckout::password('Sparxz986532!');
        \Twocheckout::sandbox(true);

        try {
            $result = \Twocheckout_Sale::stop(['sale_id' => $reference]);
        } catch (\Twocheckout_Error $e) {
            return $e->getMessage();
        }
    }

    protected function migrateToFreePlan(Subscription $sub)
    {
        $sub->plan_id = Plan::where('price', 0)->first()->id;
        $sub->save();
    }

    public function getQuiz()
    {
        return view('dashboard.quiz');
    }

    public function postUpdateNPORegistrationNumber(UpdateNPORegistrationNumberRequest $request)
    {
        $npoRegistrationNumber = $request->get('npo_registration_number');
        $user = auth()->user();
        $user->profile()->update(['npo_registration_number' => $npoRegistrationNumber]);
        alert()->success('NPO Registration Number successfully added', 'Success');
        return redirect()->back();
    }

    public function postUpdateCipcUpdateFreeEvent(Request $request)
    {
        $cipcpromocode = $request->get('free_cipc_update');
        PromoCode::findByCode($cipcpromocode);
        $user = auth()->user();
        $user->profile()->update(['cipc_update_promo_code' => $cipcpromocode]);
        alert()->success('Your Discount has been applied', 'Success');
        return redirect()->back();
    }

    public function postUpdatePMCDiscount(Request $request)
    {
        $pmc_discount_code = $request->get('discount_code');
        PromoCode::findByCode($pmc_discount_code);
        $user = auth()->user();
        $user->profile()->update(['pmc_attorneys_discount' => $pmc_discount_code]);
        alert()->success('Your Discount has been applied', 'Success');
        return back();
    }

    public function postUpdatePMCAccountantsDiscount(Request $request)
    {
        $pmc_discount_code = $request->get('discount_code');
        PromoCode::findByCode($pmc_discount_code);
        $user = auth()->user();
        $user->profile()->update(['pmc_accountants_discount' => $pmc_discount_code]);
        alert()->success('Your Discount has been applied', 'Success');
        return back();
    }

    public function postUpdateContactDetails(UpdateContactDetailsRequest $request)
    {

        $contactNumber = $request->get('cell');

        $user = auth()->user();
        $user->update(['cell' => $contactNumber]);
        $this->calculatePercentage($user);

        alert()->success('Contact details successfully updated', 'Success');
        return redirect()->intended(route('dashboard', [], false));
    }

    public function getAccountStatement()
    {
        $user = $this->userRepository->find(auth()->user()->id);
        return view('dashboard.account-statement', compact('user'));
    }

    public function download_webinar(Request $request)
    {
        if (!$request->has('privacy_policy'))
        {
            alert()->error('You cannot download the video if you do not accept our privacy policy', 'Error');
            return back();
        }

        $user = auth()->user()->id;
        DownloadWebinars::create([
            'user_id' => $user,
            'video_id' => $request->video_id,
            'webinar_title' => $request->webinar_title
        ]);

        $link = \App\Video::find($request->video_id);
        return redirect($link->download_link . '&download=1');
    }

    public function downlaoded_webinars()
    {
        $webinars = DownloadWebinars::paginate(10);
        return view('admin.events.webinars', compact('webinars'));
    }

    public function general()
    {

        return view('dashboard.general');
    }

    public function sendConfirmationEmailToProfessionalBody($user)
    {
        $job = (new SendMembershipConfirmation($user));
        $this->dispatch($job);
    }

    public function update_id_number_request($id, Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'verified' => 'required',
            'reason' => 'required'
            ]);

        if ($validator->fails()){
            alert()->error('Please check your input and try again', 'Invalid Input');
            return redirect()->back()->withInput();
        }

        if ($request->verified == 'true'){
            $user = User::find($id);
            $job = (new SendIdNumberApprovalEmailRequest($user, $request))->delay(300);;
            $this->dispatch($job);

        }else{
            alert()->error('Your ID number is not valid, Please try again', 'Invalid ID Number');
            return redirect()->back()->withInput();
        }

        alert()->success('Your request has been received', 'Thank you');
        return redirect()->back();
    }

    public function update_email_address_request($id, Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'new_email_address' => 'required:email',
            'reason' => 'required'
        ]);

        if ($validator->fails()){
            alert()->error('Please check your input and try again', 'Invalid Input');
            return redirect()->back()->withInput();
        }

        $user = User::find($id);
        $job = (new SendEmailAddressApprovalEmailRequest($user, $request))->delay(300);
        $this->dispatch($job);

        alert()->success('Your request has been received', 'Thank you');
        return redirect()->back();
    }

    public function send_statement()
    {
        $user = auth()->user();
        if(sendMailOrNot($user, 'accounts.statement')) {
        Mail::send('emails.accounts.statement', ['user' => $user], function ($message) use ($user) {
            $message->from(config('app.email'), config('app.name'));
            $message->to($user->email);
            $message->subject('Your Account Statement');
        });
        }
        alert()->success('The statement has been sent successfully!', 'Statement Sent!');
        return back();
    }

    public function webinars_on_demand(Request $request)
    {
        $webinars_ids = auth()->user()->webinarsPending()->pluck('id')->toArray();
        $user = auth()->user();
        // $webinars_ids = auth()->user()
        //     ->webinars
        //     ->pluck('id')
        //     ->toArray();
        // $webinars_ids = array_unique($webinars_ids);

        // Different view for practice package
        $activeTab = 'my_webinars';
        $browseactiveTab = 'paid_browse_videos';
        $is_practice_package = auth()->user()->subscription('cpd')->plan->is_practice;
        $practice_package_tabs = PracticePlanTabs::with('features')->orderBy('sequence')->get();
        
        if($is_practice_package) {
            // In case of practice package
            $activeTab = '';
            if(count($practice_package_tabs)) {
                $activeTab = 'tab_'.$practice_package_tabs[0]->id;
            }

            // From type field of search form
            foreach($practice_package_tabs as $tab) {
                if($request->input('type') == 'tab_'.$tab->id) {
                    $activeTab = 'tab_'.$tab->id;
                }
            }

            // From pagination key
            foreach($practice_package_tabs as $tab) {
                if($request->input('tab_'.$tab->id)) {
                    $activeTab = 'tab_'.$tab->id;
                }
            }
        }
        else {
            // In case of normal view
            if($request->input('type') == 'browse_webinars') {
                $activeTab = 'browse_webinars';
            }
            if($request->paid_browse_videos || $request->free_browse_videos) {
                $activeTab = 'browse_webinars';
            }
        }

        if($request->input('type') == 'webinar_series' || $request->webinar_series) {
            $activeTab = 'webinar_series';
        }

        if($request->input('type') == 'free_wods' || $request->free_wods) {
            $activeTab = 'free_wods';
        }

        if($request->input('browse') == 'free_browse_videos' || $request->free_browse_videos) {
            $browseactiveTab = 'free_browse_videos';
        }
        
        $webinar_categories = $this->getCategoriesByParent(0, true);
        $title = $request['title'];
        $category = $request['category'];
        $sub_category = $request['sub_category'];
        $sub_sub_category = $request['sub_sub_category'];
        $category_ids = [];

        if($sub_sub_category && $sub_sub_category!= 'null') {
            $category_ids[] = $sub_sub_category;

        } elseif($sub_category && $sub_category!= 'null') {
            $category_ids = $this->get_categories($sub_category);
            array_push($category_ids,$sub_category);

        } elseif($category && $category!= 'null') {
            $category_ids = $this->get_categories($category);
            array_push($category_ids,$category);
        }

        // Get all webinars
        $videos = null;
        $browse_videos = null;

        if($is_practice_package) {
            
            $videos = \App\Video::whereRaw(1);

            // Apply search filters
            if($request->input('type')) {
                // Search by title
                if (strlen($title)) {
                    $videos->search($title, null, true);
                    $videos->orderByRaw('relevance desc,id desc');
                }
                
                // Search by category
                if(count($category_ids)) {
                    $videos->whereIn('category', $category_ids);
                }

            }

            if (empty($title)) {
                $videos->orderBy('id', 'desc');
            }
            $videos = $videos->where('status',0);
            foreach($practice_package_tabs as $tab) {

                $tab_videos = clone $videos;
                $tab_videos->whereHas('features', function($q) use($tab){
                    $q->whereIn('slug', $tab->features->pluck('slug'));
                });
                $tab_videos=$tab_videos->paginate(6,['*'], 'tab_'.$tab->id);
                $tab_videos->appends(Input::only(['type','title', 'category', 'sub_category', 'sub_sub_category']));
                $tab->tab_videos = $tab_videos;
            }
            
        }
        else {

            if($request->input('type')) {

                $videos =  $user->webinars()
                    ->where('amount', '>', '0');


                if (strlen($title)) {
                    $videos->search($title, null, true);
                    $videos->orderByRaw('relevance desc,id desc');
                }
                else {
                    $videos->orderBy('id', 'desc');
                }
                    
                if(count($category_ids)) {
                    $videos->whereIn('category', $category_ids);
                }

                if($request->webinar_complete == 'Y') {
                    $videos->where('webinar_complete', 1);
                }
                else if($request->webinar_complete == 'N'){
                    $videos->where('webinar_complete', 0);
                }
            } 
            else 
            {
                $videos = $user->webinars()
                    ->where('amount', '>', '0')
                    ->where('webinar_complete', 0)
                    ->orderBy('id', 'desc');
            }
            $videos = $videos->where('status',0);
            $videos = $videos->groupBy('videos.id');
            $videos = $videos->paginate(6,['*'],'my_webinars');
            $videos->appends(Input::except('my_webinars','free_browse_videos', 'paid_browse_videos', 'webinar_series','free_wods'));

            //Browse Webinars
            if($request->input('type')) {
                
                $browse_videos = \App\Video::where('status',0)->whereNotIn('id',$webinars_ids);
    
                if (strlen($title)) {
                    $browse_videos->search($title, null, true);
                    $browse_videos->orderByRaw('relevance desc,id desc');
                }
                else {
                    $browse_videos->orderBy('id', 'desc');
                }
                    
                if(count($category_ids)) {
                    $browse_videos->whereIn('category', $category_ids);
                }
    
            } 
            else 
            {
                $browse_videos = \App\Video::whereNotIn('id',$webinars_ids)
                                ->orderBy('id', 'desc');
            }

            $browse_videos->where('type', 'single');
            
            // Paid browse webinars
            $paid_browse_videos = clone $browse_videos;
            $paid_browse_videos->where('amount', '>', '0');
            $paid_browse_videos = $paid_browse_videos->paginate(6,['*'],'paid_browse_videos');
            $paid_browse_videos->appends(Input::except('my_webinars','free_browse_videos', 'paid_browse_videos', 'webinar_series','free_wods'));

            // Free browse webinars
            $free_browse_videos = clone $browse_videos;
            $free_browse_videos->where('amount', '<=', '0');
            $free_browse_videos = $free_browse_videos->paginate(6,['*'],'free_browse_videos');
            $free_browse_videos->appends(Input::except('my_webinars','free_browse_videos', 'paid_browse_videos', 'webinar_series','free_wods'));



            //Free webinars
            if($request->input('type')) {
                
                $free_wods = $user->webinars()
                    ->where('amount', '0')
                    ->where('status',0)
                    ->where('type', 'single');
    
                if (strlen($title)) {
                    $free_wods->search($title, null, true);
                    $free_wods->orderByRaw('relevance desc,id desc');
                }
                else {
                    $free_wods->orderBy('id', 'desc');
                }
                    
                if(count($category_ids)) {
                    $free_wods->whereIn('category', $category_ids);
                }
                
                if($request->webinar_complete) {
                    $free_wods->where('webinar_complete', 1);
                }
                else {
                    $free_wods->where('webinar_complete', 0);
                }

            } 
            else 
            {
                $free_wods = $user->webinars()
                    ->where('amount', '0')
                    ->where('status',0)
                    ->where('type', 'single')
                    ->where('webinar_complete', 0)
                    ->orderBy('id', 'desc');
            }
            $free_wods = $free_wods->groupBy('videos.id');
            $free_wods = $free_wods->paginate(6,['*'],'free_wods');
            $free_wods->appends(Input::except('my_webinars','free_browse_videos', 'paid_browse_videos', 'webinar_series','free_wods'));


        }

        // Webinar series
        $webinar_series = \App\Video::where('status',0)
            ->where('type', 'series')
            ->where(function($query) use($webinars_ids) {
                $query->whereIn('id',$webinars_ids);
                $query->orWhereHas('webinars', function($q) use($webinars_ids) {
                    $q->whereIn('series_videos.video_id', $webinars_ids);
                });
            });

        if($request->input('type')) {

            if (strlen($title)) {
                $webinar_series->search($title, null, true);
                $webinar_series->orderByRaw('relevance desc,id desc');
            }
            else {
                $webinar_series->orderBy('id', 'desc');
            }
                
            if(count($category_ids)) {
                $webinar_series->whereIn('category', $category_ids);
            }

        } 
        $webinar_series = $webinar_series->paginate(6,['*'],'webinar_series');
        $webinar_series->appends(Input::except('my_webinars','free_browse_videos', 'paid_browse_videos', 'webinar_series','free_wods'));

        return view('dashboard.webinars_on_demand', compact('webinar_categories', 'videos', 'browse_videos', 'request','sub_category','sub_sub_category', 'title', 'category', 'cpd','activeTab', 'is_practice_package', 'practice_package_tabs', 'webinar_series', 'free_wods', 'browseactiveTab', 'paid_browse_videos', 'free_browse_videos'));
    }

    public function getCategoriesByParent($parent_id=null, $is_dashboard = false) {

        $categories = collect();
        $topics = Category::with('parent', 'parent.parent')
            ->select('categories.*')
            ->join('videos', 'categories.id', '=', 'videos.category');

        // Check for pratice plan
        if($is_dashboard) {

            $is_practice_package = auth()->user()->subscription('cpd')->plan->is_practice;
            if($is_practice_package) {
                $topics->join('feature_video', 'videos.id', '=', 'feature_video.video_id')
                ->join('practice_plan_tabs_features', 'feature_video.feature_id','=','practice_plan_tabs_features.feature_id');
            }
        }
            
        $topics = $topics->where('videos.tag', 'studio')
            ->where('videos.status',0)
            // ->where('videos.type','single')
            ->groupBy('categories.id')
            ->get();
        
        // All categories with videos
        foreach($topics as $topic) {

            $categories->push($topic);
            if($topic->parent) {

                $categories->push($topic->parent);

                if($topic->parent->parent) {
                    $categories->push($topic->parent->parent);
                }
            }
        }
        
        // Filter if parent category id available
        if($parent_id!==null) {
            $categories = $categories->filter( function($cat) use($parent_id){
                if($cat->parent_id==$parent_id) {
                    return true;
                }
                return false;
            });
        }

        $categories=$categories->unique('id');
        $categories->each(function($category, $key) {
            $category->title = trim($category->title);
            return $category;
        });
        $categories=$categories->sortBy('title');


        return $categories;

    }

    protected function get_categories($cat) {
        $arrCategory=[];
        $category = Category::find($cat);
        $childCategories = $category->childCategory();

        // If child categories exists than call recursive function
        foreach($childCategories as $cat) {
            $arrCategory[] = (string)$cat->id;
            $arrCategory = array_merge($arrCategory, $this->get_categories($cat->id));
        }
        return $arrCategory;
    }

    public function rewards()
    {
        $sponsorList = SponsorList::where('is_active','1')->orderBy('created_at', 'DESC')->get(); 
        return view('dashboard.rewards',compact('sponsorList'));
    }

    public function contact_email(Request $request)
    {
        $user = auth()->user();
        $subscription = Plan::where('id', $request->id)->first();
        $rep = Rep::nextAvailable();   
        Mail::send('emails.plan_mail', ['plan' => $subscription,'user' => $user], function ($message) use ($user,$rep) {
            $message->from(config('app.email'), config('app.name'));
            $message->to($rep->email);
            $message->subject('Change subscription request');
        });
        $user->update([
            'round_robin_notified' => true,
            'round_robin_notified_date' => Carbon::now()
        ]);
        $rep->update(['emailedLast' => Carbon::now()]);
        return response()->json(['status' => 'success']);
    }

    public function check_event($event_id, $user_id) {
        $user_id = base64_decode($user_id);
        $event_id = base64_decode($event_id);

        $user = User::find($user_id);
        $event = Event::find($event_id);
        if($user && $event) {
            $ticket = Ticket::where('user_id', $user->id)->where('event_id', $event->id)->first();
            if($ticket) {
                return redirect()->route('dashboard.tickets.links-and-resources', $ticket->id);
            }
        }
        if( !$event || !$user || $event->slug == '' || !$event->slug ) {
            return redirect()->route('events.index');
        }
        return redirect()->route('events.show', $event->slug);
    }
}
