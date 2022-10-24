<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Subscriptions\Models\Plan;
use App\Http\Controllers\Controller;
use App\AppEvents\Event;
use App\Http\Controllers\Admin\AdminEventSyncController;

class ApiController extends Controller
{
    /**
     * getplans
     *
     * @return void
     */
    public function getplans(Request $request,$id)
    {
        $plans = Plan::find($id);
        $plan_event = $plans->events->pluck('id')->toArray();
        $events = Event::with('presenters','venues','pricings','links')->whereIn('id',$plan_event)->get();
    
        return response()->json([
            'data' => $events,
            'message' => 'Events data',
        ], 200);
    }
  
    /**
     * getPlanList
     *
     * @return void
     */
    // Get all plan api
    public function getPlanList()
    { 
        // $plans = Plan::all()->pluck('name','id');
        // return response()->json([
        //     'data' => $plans,
        //     'message' => 'Plans data',
        // ], 200);
        $plans = Plan::select('id','name')->get()->toArray();
        return response()->json($plans);
    }

    /**
     * getEventList
     *
     * @return void
     */
    // Get all events api    
    public function getEventList()
    { 
        // $events = Event::all();
        $events = Event::all()->pluck('name','id')->toArray();
        return response()->json($events);
    }

    // Get particular events api by name  
    public function getEventByName($name)
    { 
        $name = url_decode($name);
        $events = Event::with('presenters','venues','venues.pricings', 'venues.pricings.recordings', 'venues.pricings.recordings.video', 'venues.pricings.recordings.video.categories', 'links','files','venues.dates','extras','assessments','assessments.questions','assessments.questions.options','venues.pricings.webinars', 'promoCodes')->where('name',$name)->get();
        
        return response()->json($events);
    }

    public function getEventById($id)
    {
        $events = Event::with('presenters','venues','venues.pricings', 'venues.pricings.recordings', 'venues.pricings.recordings.video', 'venues.pricings.recordings.video.categories', 'links','files','venues.dates','extras','assessments','assessments.questions','assessments.questions.options','venues.pricings.webinars', 'promoCodes')->where('id',$id)->get();
        return response()->json($events);
    }

    public function getEventByReferenceId($reference_id)
    {
        $events = Event::with('presenters','venues','venues.pricings', 'venues.pricings.recordings', 'venues.pricings.recordings.video', 'venues.pricings.recordings.video.categories', 'links','files','venues.dates','extras','assessments','assessments.questions','assessments.questions.options','venues.pricings.webinars', 'promoCodes')->where('reference_id',$reference_id)->get();
        return response()->json($events);
    }

    public function syncEvent($name, $reference_id) {
        $name = url_decode($name);
        $event = Event::where('reference_id',$reference_id)->first();
        
        if($event) {
            $event->reference_id = $reference_id;
            $event->save();
        }

        $eventSync = new AdminEventSyncController;
        $myRequest = new Request();
        $myRequest->replace(['event' => $name, 'reference_id' => $reference_id]);
        $response = $eventSync->getEventListSync($myRequest);

        return response()->json(['code' => 'success'], 200);
    }

    public function asyncEvent($reference_id) {
        // $name = url_decode($name);
        $event = Event::where('reference_id',$reference_id)->first();

        if($event) {
            // $event->reference_id = null;
            $event->delete();
        }
        return response()->json(['code' => 'success'], 200);
    }
}
