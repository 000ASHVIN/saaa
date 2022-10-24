<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Event;
use App\AppEvents\EventRepository;
use App\AppEvents\Ticket;
use App\Assessment;
use App\Blog\Category;
use App\Body;
use App\Http\Requests\EventCreationRequest;
use App\Jobs\AssignWebinarAttendeesToSendinBlue;
use App\NumberValidator;
use App\Presenters\Presenter;
use App\Store\Link;
use App\Store\Listing;
use App\Store\Product;
use App\Subscriptions\Models\Feature;
use App\Subscriptions\Models\Plan;
use App\Video;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\WebinarInviteJob;
use App\Users\User;
use App\eventNotified;

use GuzzleHttp\Client;
use Exception;
use App\Repositories\Sendinblue\SendingblueRepository;

class AdminEventsController extends Controller
{
    private $eventRepository;
    private $sendingblueRepository;
    public function __construct(EventRepository $eventRepository, SendingblueRepository $sendingblueRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->sendingblueRepository = $sendingblueRepository;
    }

    public function index()
    {
        $plans = Plan::all()->sortBy('name')->pluck('id', 'name');
        $presenters = Presenter::all()->pluck('name', 'id' );
        return view('admin.event.create', compact('plans', 'presenters'));
    }

    public function store(EventCreationRequest $request)
    {
        $this->convertDateTime($request, $start_date, $end_date, $next_date);
        $event = $this->createEvent($request, $start_date, $end_date, $next_date);

        alert()->success('Your event has been created!', 'Success!');
        return redirect()->route('admin.event.show', $event->slug);
    }

    public function show($slug)
    {
        $bodies = Body::all();
        $features = Feature::all();
        $assessments = Assessment::all();
        $event = Event::with('extras', 'venues.dates', 'venues.pricings','pricings.tickets.user')->where('slug',$slug)->first();
        $categories = Category::all()->pluck('title', 'id');
        return view('admin.event.show', compact('event', 'features', 'assessments', 'bodies', 'categories'));
    }

    public function update(EventCreationRequest $request, $slug)
    {
        $event = $this->eventRepository->findEvent($slug);
        $event_name_old = $event->name;
        $this->updateEvent($request, $event);
        $event->save();

        // Sync the presenters, if none remove all.
        $event->presenters()->sync(! $request->presenter ? [] : $request->presenter);

        // Sync the tags for this event
        $event->categories()->sync(! $request->tags ? [] : $request->tags);

        $client = new Client([
            'base_uri' => env('EVENT_API'),
        ]);
        
        // Sync events between SA Account Academy and Tax Faculty
        if($request->has('sync_event') && $request->sync_event == 'yes') {
            //sync event with ttf
            $response = $client->get('api/event/'.url_encode($event_name_old).'/sync/'.$event->id);
            $syncTaxFacultyEvent = json_decode($response->getBody()->getContents());

            //get event data from ttf
            $getEvent = $client->get('api/event/'.$event->id.'/reference_id');
            $event_data = json_decode($getEvent->getBody()->getContents());

            if(count($event_data) > 0) {
                $event->is_synced_event = true;
                $event->save();
            }

        } else {
            $event->is_synced_event = false;
            $event->save();

            //get event data from ttf
            $getEvent = $client->get('api/event/'.$event->id.'/reference_id');
            $event_data = json_decode($getEvent->getBody()->getContents());

            if(count($event_data) > 0) {
                $response = $client->get('api/event/async/'.$event->id);
                $asyncTaxFacultyEvent = json_decode($response->getBody()->getContents());
            }
        }

       

        alert()->success('Your Event has been updated!', 'Success!');
        return redirect()->route('admin.event.show', $event->slug);
    }

    public function sync_event(Request $request, $slug) {
        $event = $this->eventRepository->findEvent($slug);
        $event_name_old = $event->name;

        $client = new Client([
            'base_uri' => env('EVENT_API'),
        ]);

        //sync event with ttf
        $response = $client->get('api/event/'.url_encode($event_name_old).'/sync/'.$event->id);
        $syncTaxFacultyEvent = json_decode($response->getBody()->getContents());

        //get event data from ttf
        $getEvent = $client->get('api/event/'.$event->id.'/reference_id');
        $event_data = json_decode($getEvent->getBody()->getContents());

        if(count($event_data) > 0) {
            $event->is_synced_event = true;
            $event->save();
        }

        alert()->success('Your Event has been synced!', 'Success!');
        return redirect()->route('admin.event.show', $event->slug);
    }

    
    /**
     * @param EventCreationRequest $request
     * @param $start_date
     * @param $end_date
     * @param $next_date
     * @return Event
     */
    public function createEvent(EventCreationRequest $request, $start_date, $end_date, $next_date)
    {
        $event = new Event([
            'type' => $request['type'],
            'name' => $request['name'],
            'end_date' => $end_date,
            'end_time' => $end_date,
            'next_date' => $next_date,
            'start_date' => $start_date,
            'start_time' => $start_date,
            'is_active' => $request['is_active'],
            'category' => $request['category'],
            'description' => $request['description'],
            'is_redirect' => $request['is_redirect'],
            'redirect_url' => $request['redirect_url'],
            'featured_image' => $request['featured_image'],
            'short_description' => $request['short_description'],
            'is_open_to_public' => $request['is_open_to_public'],
            'subscription_event' => $request['subscription_event'],
            'registration_instructions' => $request['registration_instructions'],
            'video_title' => $request['video_title'],
            'video_url' => $request['video_url'],
            'background_url' => $request['background_url']
        ]);
        $event->save();

        // Sync the presenters for this event
        $event->presenters()->sync(! $request->presenter ? [] : $request->presenter);

        // Sync the tags for this event
        $event->categories()->sync(! $request->tags ? [] : $request->tags);
        return $event;
    }

    /**
     * @param EventCreationRequest $request
     * @param $start_date
     * @param $end_date
     * @param $next_date
     */
    public function convertDateTime(EventCreationRequest &$request, &$start_date, &$end_date, &$next_date)
    {
        $start_date = $request['start_date'] = Carbon::createFromTimestamp(strtotime($request['start_date'] . $request['start_time'] . ':00'));
        $end_date = $request['timestamp'] = Carbon::createFromTimestamp(strtotime($request['end_date'] . $request['end_time'] . ':00'));
        $next_date = Carbon::parse($request['next_date']);
    }

    /**
     * @param EventCreationRequest $request
     * @param $event
     */
    public function updateEvent(EventCreationRequest $request, $event)
    {
        //$event->slug = null;
        $event->update([
            'name' => $request->name,
            'subscription_event' => $request->subscription_event,
            'is_open_to_public' => $request->is_open_to_public,
            'is_active' => $request->is_active,
            'type' => $request->type,
            'category' => $request->category,
            'start_date' => $request->start_date,
            'end_date' => Carbon::parse($request->end_date),
            'end_time' => Carbon::createFromTimestamp(strtotime($request->end_date . $request->end_time . ':00')),
            'next_date' => Carbon::parse($request->next_date),
            'start_time' => Carbon::createFromTimestamp(strtotime($request->start_date . $request->start_time . ':00')),
            'featured_image' => $request->featured_image,
            'is_redirect' => $request->is_redirect,
            'redirect_url' => $request->redirect_url,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'registration_instructions' => $request->registration_instructions,
            'video_title' => $request->video_title,
            'video_url' => $request->video_url,
            'background_url' => $request['background_url']
        ]);
    }

    public function search(Request $request)
    {
        $search = str_replace('--', '-', str_replace(' ', '-', strtolower(preg_replace('/[^a-zA-Z0-9 .]/', '', $request['event_name']))));
        $events = Event::where('slug', 'LIKE', '%'.$search.'%')->whereNull('reference_id')->get();
        return view('admin.events.search_result', compact('events'));
    }

    public function exportStats($slug)
    {
        $event = $this->eventRepository->findEvent($slug);
        $tickets = Ticket::with('user', 'venue', 'dates', 'extras', 'pricing', 'dietaryRequirement', 'invoice', 'invoice.transactions')->where('event_id', $event->id)->get();
        $groupedTickets = $tickets->groupBy('venue.name');
        $numberValidator = new NumberValidator();
        if($groupedTickets->count() == 0){
            alert()->error('No Data found related to this event', 'Error!');
            return redirect()->back();
        }
        Excel::create($event->name, function($excel) use($groupedTickets, $numberValidator) {
            foreach ($groupedTickets as $key => $value){
                $name = preg_replace('/[^a-zA-Z0-9 .]/', '', str_replace("Sanlam Training Centre ","",$key));
                $excel->sheet(str_limit($name, 27), function($sheet) use($value, $numberValidator) {
                    $sheet->appendRow([
                        'First Name',
                        'Last Name',
                        'Email',
                        'Subscription',
                        'Company',
                        'Cell',
                        'Venue',
                        'Invoice Number',
                        'Invoice Total',
                        'Invoice Discount',
                        'Invoice Credit',
                        'Invoice VAT',
                        'Amount  Paid',
                        'Balance  due',
                        'Status',
                        'Dietary Requirements',
                        'Dates',
                        'Extras',
                        'Attended',
                        'Booked'
                    ]);

                    foreach ($value as $ticket){
                        $subscription = ($ticket->user->subscribed('cpd') ? $ticket->user->subscription('cpd')->plan->name : "Free Member");
                        $reff = ($ticket->invoice ? $ticket->invoice->reference : "-");
                        $invoice = ($ticket->invoice ? $ticket->invoice : false);
                        $balance = ($ticket->invoice ? $ticket->invoice->balance : "-");
                        $status = ($ticket->invoice ? $ticket->invoice->status : "-");

                        try {
                            $phone = ($ticket->user->cell ? ((strlen($ticket->user->cell)>5)?$numberValidator->format($ticket->user->cell):"-"): "-");
                        }
                        catch(\Exception $e) {
                            $phone = $ticket->user->cell;
                        }
                        
                        $dates = $ticket->dates->pluck('date');
                        $extras = (count($ticket->extras) >=1 ? $ticket->extras->pluck('name') : "None");
                        $company = ($ticket->user->profile)?($ticket->user->profile->company ? ucwords($ticket->user->profile->company) : "-"):"-";

                        $bodies = [];
                        $balance_due = 0;
                        if($invoice) {
                            $debit =  $invoice->transactions->where('type', 'debit')->sum('amount');
                            $credit =  $invoice->transactions->where('type', 'credit')->sum('amount');
    
                            $credit > $debit ? $balance_due =  0 : $balance_due = $debit - $credit;
                        }


                        if($ticket->user->additional_professional_bodies){
                            foreach ($ticket->user->additional_professional_bodies as $bodyId){
                                $body = Body::find($bodyId);
                                array_push($bodies, $body->title);
                            }

                            if ($ticket->user->body){
                                array_push($bodies, Body::find($ticket->user->body->id)->title);
                            }

                            $sheet->appendRow([
                                ucfirst(strtolower($ticket->first_name)),
                                ucfirst(strtolower($ticket->last_name)),
                                ucfirst(strtolower($ticket->email)),
                                $subscription,
                                $company,
                                $phone,
                                $ticket->venue->name,
                                $reff,
                                ($invoice ? $invoice->total : '0'),
                                ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                                ($invoice ? (($invoice->status == 'credit noted' || $invoice->status == 'cancelled') ?  $invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : 0) : '0'),
                                ($invoice) ?$invoice->vat_rate."%" : '0%',
                                ($invoice ? (($invoice->status == 'paid') ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0') : '0'),
                                ($invoice ? $balance_due : '0'),

                                $status,
                                $ticket->dietary_requirement_name,
                                preg_replace('/[^a-zA-Z0-9 .]/', '', $dates),
                                preg_replace('/[^a-zA-Z0-9 .]/', '', $extras),
                                ($ticket->attended ? "Yes" : "No"),
                                date_format(Carbon::parse($ticket->created_at), 'd F Y'),
                                implode(', ',array_values(array_unique($bodies)))
                            ]);
                        }else{
                            $sheet->appendRow([
                                ucfirst(strtolower($ticket->first_name)),
                                ucfirst(strtolower($ticket->last_name)),
                                ucfirst(strtolower($ticket->email)),
                                $subscription,
                                $company,
                                $phone,
                                $ticket->venue->name,
                                $reff,
                                ($invoice ? $invoice->total : '0'),
                                ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                                ($invoice ? (($invoice->status == 'credit noted' || $invoice->status == 'cancelled') ?  $invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : 0) : '0'),
                                ($invoice) ?$invoice->vat_rate."%" : '0%',
                                ($invoice ? (($invoice->status == 'paid') ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0') : '0'),
                                ($invoice ? $balance_due : '0'),
                                $status,
                                $ticket->dietary_requirement_name,
                                preg_replace('/[^a-zA-Z0-9 .]/', '', $dates),
                                preg_replace('/[^a-zA-Z0-9 .]/', '', $extras),
                                ($ticket->attended ? "Yes" : "No"),
                                date_format(Carbon::parse($ticket->created_at), 'd F Y'),
                                ($ticket->user->body ? $ticket->user->body->title : ($ticket->user->specified_body ? : "N/A"))
                            ]);
                        }

                    }
                });
            }
        })->export('xls');
    }

    public function uploadStats($event)
    {
        $event = Event::findBySlug($event);
        $tickets = $event->tickets;

        // Create the folder on SendinBlue.
        try {
            $data = [
                'name' => 'Webinar Attendess for '.$event->name.' '.date_format(Carbon::now(), 'd F Y'),
                'folderId' => (int)env('WEBINAR_PARENT_FOLDER_ID')
            ];
            $folder = $this->sendingblueRepository->createList($data);
    
            $webinarAttendeesJob = (new AssignWebinarAttendeesToSendinBlue($tickets, $folder));
            $this->dispatch($webinarAttendeesJob);
        } catch (Exception $e) {}

        alert()->success('We are uploading your contacts, Please Check SendinBlue In 5 Minutes', 'Success!');
        return redirect()->route('admin.events.index');
    }

    public function onlineStore(Request $request, $eventSlug)
    {
        DB::transaction(function () use($eventSlug){
            $event = Event::findBySlug($eventSlug);
            $venue = $event->venues->where('type', 'online')->first();

            if (! $venue){
                alert()->error('No online venue found for this event, please create online venue for this event in order to publish to store.', 'Error!')->persistent('close');
                return redirect()->back();
            }

            $price = $venue->pricings->first();
            $listings = Listing::where('title', 'LIKE', Carbon::now()->year.'%Webinar Recordings%')->get();

            if (! $listings){
                alert()->error('Please ensure that you have a store listing created called "Webinar Recordings" and or check that this event has videos', 'Error!')->persistent('close');
                return redirect()->back();
            }

            $videos = $price->recordings;

            $product = new Product([
                'topic'         => 'Recording',
                'year'          => Carbon::parse($price->start_time)->year,
                'title'         => $event->name,
                'price'         => $price->price,
                'is_physical'   => false,
                'cpd_hours'     => $price->cpd_hours,
                'stock'         => '9999999',
                'allow_out_of_stock_order' => true
            ]);
            $product->save();

            // Save the assessments to the store item
            foreach ($event->assessments as $assessment){
                $product->assessments()->save($assessment);
            }

            // Save product to the listing for this year.
            foreach ($listings as $listing){
                $product->listings()->save($listing);
            }

            // Saving the videos as links
            if (count($videos)){
                foreach ($videos as $videoId){
                    $video = Video::find($videoId->video_id);
                    $link = new Link([
                        'name' => 'Recording',
                        'url' => $video->download_link,
                        'instructions' => null,
                        'secret' => null
                    ]);
                    $product->links()->save($link);
                }
            }

            if (count($event->links)){
                foreach ($event->links as $link){
                    $link = new Link([
                        'name' => $link->name,
                        'url' => $link->url,
                        'instructions' => $link->instructions,
                        'secret' => $link->secret
                    ]);
                    $product->links()->save($link);
                }
            }
            alert()->success('Your product has been created.', 'Success!');
        });
        return redirect()->route('admin.events.index');
    }
    public function notify($id)
    {
        $event = Event::findBySlug($id);
        $users = collect();
        $user = User::first();
        
        //$this->sendInviteTo($user, $event);
        $users = $this->loopAndPushUser($event,$users);
        foreach($users->unique() as $user){
            
            $this->sendInviteTo($user, $event);
        }
        return redirect()->back();
    }

    public function scheduleNotifications($id)
    {
        $scheduled = Event::where('notification_status', 'scheduled')->get();

        if(!$scheduled->count()) {

            $event = Event::findBySlug($id);
            if(!$event) {
                alert()->error('Something went wrong!', 'Error!');
                return redirect()->back();
            }

            if($event->notification_status=='not_scheduled') {
                $event->update([
                    'notification_status' => 'scheduled'
                ]);
                alert()->success('Event notifications are scheduled!', 'Success!');
                return redirect()->back();
            }
            else {
                alert()->error('Notifications already scheduled for the events, you can not schedule it again!', 'Error!');
                return redirect()->back();
            }

        }
        else {

            alert()->error('Notifications already scheduled for other event, please try to schedule this notifications tomorrow!', 'Error!');
            return redirect()->back();

        }
        

    }

    // This will loop through the event tickets and push the ticket user to new users collection
    public function loopAndPushUser($event, $users)
    {
        foreach ($event->tickets as $ticket) {
            if ($ticket->user) {
                if ($ticket->user->settings && key_exists('event_notifications', $ticket->user->settings)) {
                    if ($ticket->invoice_order && $ticket->invoice_order->paid) {
                        if($ticket->user->status != 'cancelled'){
                            $users->push($ticket->user);
                        }
                    } elseif ($ticket->invoice && $ticket->invoice->paid) {
                        if($ticket->user->status != 'cancelled'){
                            $users->push($ticket->user);
                        }
                    } elseif (is_null($ticket->invoice) && is_null($ticket->invoice_order)) {
                        if($ticket->user->status != 'cancelled'){
                            $users->push($ticket->user);
                        }
                    }
                }
            }
        }
        return $users;
    }

    // Send email to users
    public function sendInviteTo($user, $event)
    {
        $date = date_format($event->venues->where('type', 'online')->first()->pricings->first()->actualDate(), 'd F Y') . ' ' . Carbon::parse($event->start_time)->format('H:i') . ' - ' . Carbon::parse($event->end_time)->format('H:i');
        $subject = 'Reminder:' . ' ' . $event->name . ' ,' . $date;

        try {
            $EventName = $event->name;
            $EventDate = $date;
            $webinars = collect();
            $event->venues->where('type', 'online')->first()->pricings->take(1)->filter(function ($pricing) use ($webinars) {
                if (count($pricing->webinars)) {
                    $webinars->push($pricing->webinars);
                }
            });

            $job = (new WebinarInviteJob($user, $subject, $EventName, $EventDate, $webinars->first()));
            $this->dispatch($job);
        } catch (\Exception $exception) {
            $this->error('Could not send invite to ' . $user->first_name . ' ' . $user->last_name . ' ' . $user->email);
            $this->logEntry($user, $subject = '(Failed!) Unable to send email..', $event);
        }
    }
    public function logEntry($user, $subject, $event)
    {
        eventNotified::create([
            'full_name' => $user->first_name . ' ' . $user->last_name,
            'email_address' => $user->email,
            'subscription' => ($user->subscription('cpd')->plan->name) ?: 'Free Plan',
            'mobile' => ($user->profile->cell) ?: 'None',
            'subject_line' => $subject,
            'event_name' => $event->name
        ]);
    }
}
