<?php

namespace App\AppEvents;

use Carbon\Carbon;

/**
 * Class EventRepository
 * @package App\AppEvents
 */
class EventRepository
{
    /**
     * @var \App\AppEvents\Event
     */
    protected $event;

    /**
     * EventRepository constructor.
     * @param \App\AppEvents\Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function all()
    {
        return $this->event->orderBy('start_date', 'DESC');
    }

    public function upcoming()
    {
        return $this->event->where('end_date', '>=', Carbon::now())->with(['tickets', 'venues', 'extras'])->orderBy('start_date');
    }

    public function getAll()
    {
        return $this->event->whereNull('reference_id')->with(['venues', 'venues.dates', 'venues.pricings', 'extras'])->orderBy('start_date', 'DESC')->where('end_date', '>=', Carbon::now())->where('is_active', true)->get();
    }

    /**
     * @param int $count
     * @return mixed
     */
    public function getPublished($count = 5)
    {
        return $this->event->whereNull('reference_id')->where('start_date', '>=', Carbon::now())->where('published_at', '<=', Carbon::now())->where('is_active', true)->with('venues', 'extras')->orderBy('start_date', 'asc')->paginate($count);
    }
    
    /**
     * @param int $count
     * @return mixed
     */
    public function getPublishedEvent($count = 5)
    {
        return $this->event->where('start_date', '>=', Carbon::now()->format('Y-m-d'))->where('published_at', '<=', Carbon::now())->where('is_active', true)->with('venues', 'extras')->orderBy('start_date', 'asc')->paginate($count);
    }

    public function getUpcomingUnpaginated()
    {
        return $this->event->whereNull('reference_id')->where('end_date', '>=', Carbon::now())->where('is_open_to_public',true)->where('is_active', true)->orderBy('start_date', 'asc')->get();
    }

    public function getUpcoming($count = 5)
    {
        return $this->event->whereNull('reference_id')->whereDate('end_date', '>=', Carbon::today()->format('Y-m-d'))->where('is_open_to_public',true)->with(['tickets', 'venues', 'extras'])->where('is_active', true)->orderBy('start_date', 'asc')->paginate($count);
    }

    public function getUpcomingFilterEvents($filters=[],$count = 5)
    {
        
        $upcomingEvents =  $this->event->select('events.*')
        ->where('end_date', '>=', Carbon::now())
        ->where('is_open_to_public',true)
        ->leftjoin('category_event','events.id','=','category_event.event_id')
        ->join('event_venue','event_venue.event_id','=','events.id')
        ->join('dates','dates.venue_id','=','event_venue.venue_id')
        ->join('venues','venues.id','=','event_venue.venue_id')
        ->join('pricings','venues.id','=','pricings.venue_id')
        ->where('venues.is_active',true)
        ->with(['tickets', 'venues', 'extras'])
        ->where('events.is_active', true)
        ->where('dates.is_active',true)
        ->where('dates.date', '>=', Carbon::now());

        // Filters on events
        if(isset($filters['title']) && !empty($filters['title'])) {
            $upcomingEvents->search($filters['title'],null,true);
            $upcomingEvents->orderByRaw('relevance desc, events.start_date asc');
        }
        else {
            $upcomingEvents->orderBy('events.start_date','asc');
        }

        if(isset($filters['categories']) && !empty($filters['categories'])) {
            $upcomingEvents->where('category_event.category_id',$filters['categories']);
        }
        
        if(isset($filters['subscription']) && !empty($filters['subscription'])) {
            $currentPlan = auth()->user()->subscription('cpd')->plan;
            $events = $currentPlan->getPlanEvents();
            $currentPlanEvents = $events->pluck('id');
            if($filters['subscription']=='yes') {
                $upcomingEvents->whereIn('events.id', $currentPlanEvents);
            }
            else {
                $upcomingEvents->whereNotIn('events.id', $currentPlanEvents);
            }
        }

        if(isset($filters['paid'])) {
            if($filters['paid']) {
                $upcomingEvents->where('pricings.price', '>', 0.00);
            }
            else {
                $upcomingEvents->where('pricings.price', '=', 0.00);
            }
        }

        $upcomingEvents->groupBy('events.id');
        return $upcomingEvents->paginate($count,['*'], 'upcoming');
    }

    public function getPast($count = 5)
    {
        return $this->event->whereNull('reference_id')->where('end_date', '<', Carbon::now())->with(['tickets', 'venues', 'extras'])->where('is_active', true)->orderBy('start_date', 'desc')->paginate($count);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->event->whereNull('reference_id')->with('venues.dates', 'venues.pricings', 'extras')->find($id);
    }

    public function findEvent($slug)
    {
        return $this->event->whereNull('reference_id')->with('venues', 'extras')->where('slug', $slug)->firstOrFail();
    }

    /**
     * @param $slug
     * @param null $request
     * @return mixed
     */
    public function findBySlug($slug, $request = null)
    {
        if (auth()->guest() || !$request || !$request->get('admin', false)) {
            $event = $this->event->where('slug', $slug)->with('extras', 'activeVenues.dates', 'activeVenues.pricings')->firstorFail();
            unset($event->venues);
            $event->venues = $event->activeVenues;
        } else
            $event = $this->event->where('slug', $slug)->with('extras', 'venues.dates', 'venues.pricings')->firstOrFail();
        return $event;
    }

    public function findWith($id, $with = [])
    {
        return $this->event->with($with)->findOrFail($id);
    }

    public function findStatsWith($id, $with = [])
    {
        return $this->event->with($with)->findOrFail($id);
    }

    public function upcomingWebinar()
    {
        // Collect the new events
        $events = collect();

        // Step 1 get the event
        $filtered = $this->event->with('pricings', 'venues', 'venues.dates')->get();

        // step 2 check if the Webinar Venue date is today or tomorrow
        $filtered->each(function($event) use($events){
            if ($event->venues() && $event->is_active){
                foreach ($event->venues->where('type', 'online') as $venue){
                    if (count($venue->dates->where('is_active',1))){
                        if (Carbon::parse($venue->dates->where('is_active',1)->first()->date) == Carbon::today()->startOfDay() || Carbon::parse($venue->dates->where('is_active',1)->first()->date) == Carbon::tomorrow()->startOfDay()){
                            $events->push($event);
                        }
                    }
                }
            }
        });
        return $events;
    }

    public function upcomingEventForBookingReminder()
    {
        // Collect the new events
        $events = collect();

        // Step 1 get the event
        $filtered = $this->event->with('pricings', 'venues', 'venues.dates')->get();

        // step 2 check if the Webinar Venue date is today, tomorrow or after 5 working days
        $filtered->each(function($event) use($events){
            if ($event->venues() && $event->is_active){
                foreach ($event->venues->where('type', 'online') as $venue){
                    if (count($venue->dates)){
                        if (Carbon::parse($venue->dates->first()->date) == Carbon::today()->addWeekdays(1)->startOfDay() ||
                        Carbon::parse($venue->dates->first()->date) == Carbon::today()->addWeekdays(5)->startOfDay()){
                            $events->push($event);
                        }
                    }
                }
            }
        });
        return $events;
    }

    public function nextWebinar()
    {
        // Collect the new events
        $events = collect();

        // Step 1 get the event
        $filtered = $this->event->with('pricings', 'venues', 'venues.dates')->get();

        // step 2 check if the Webinar Venue date is today or tomorrow
        foreach ($filtered as $event){
            if ($event->venues() && $event->is_active){
                foreach ($event->venues->where('type', 'online') as $venue){
                    if (count($venue->dates) > 0){
                        if (Carbon::parse($venue->dates->first()->date) == Carbon::today()->startOfDay() || Carbon::parse($venue->dates->first()->date) == Carbon::tomorrow()->startOfDay()){
                            $events->push($event);
                        }
                    }
                }
            }
        }
        return $events;
    }

    public function findEventsWithPromoCodes()
    {
        $events = collect();
        $this->event->with('promoCodes')->get()->each(function($event) use($events){
           if (count($event->promoCodes)){
               $events->push($event);
           }
        });
        return $events->sortByDesc('id');
    }
}