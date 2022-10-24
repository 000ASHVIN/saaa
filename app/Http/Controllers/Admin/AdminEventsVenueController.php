<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Date;
use App\AppEvents\EventRepository;
use App\AppEvents\Pricing;
use App\Repositories\Venue\VenueRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SyncEvent;
use Illuminate\Support\Facades\DB;
use Validator;

class AdminEventsVenueController extends Controller
{
    private $eventRepository, $venueRepository;
    public function __construct(EventRepository $eventRepository, VenueRepository $venueRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->venueRepository = $venueRepository;
    }

    public function index()
    {
        return view('admin.event.index');
    }

    public function store(Request $request, $event)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address_line_one' => 'required'
        ]);

        if ($validator->fails()){
            alert()->warning('please check your fields and try again', 'Warning');
        }else{
            $data = $request->all();
            alert()->success('Your venue has been created!', 'Success!');
            $event = $this->eventRepository->findEvent($event);
            $data['start_time'] = date('Y-m-d H:i:s',strtotime($data['start_time']));
            $data['end_time'] = date('Y-m-d H:i:s',strtotime($data['end_time']));
            $venue = $this->venueRepository->createVenue($data);
            $event->venues()->attach($venue);

            $sync_event = new SyncEvent();
            $sync_event->sync_event($event);
        }
        return back()->withInput(['tab' => 'venues']);
   }

    public function update(Request $request, $event, $id)
    {
        $data = $request->all();
        $data['start_time'] = date('Y-m-d H:i:s',strtotime($data['start_time']));
        $data['end_time'] = date('Y-m-d H:i:s',strtotime($data['end_time']));
        $venue = $this->venueRepository->findVenue($id);
        $venue->update($data);

        $sync_event = new SyncEvent();
        $sync_event->sync_event($venue->events->first());
        alert()->success('Venue has been updated', 'Success!');
        return back()->withInput(['tab' => 'venues']);
   }

    public function destroy($id)
    {
        $venue = $this->venueRepository->findVenue($id);
        $event = $venue->events->first();
        $dates = Date::where('venue_id', $venue->id)->get();
        $pricings = Pricing::where('venue_id', $venue->id)->get();

        DB::transaction(function () use($venue, $dates, $pricings){

            $pricings->each(function ($pricing){
                $pricing->delete();
            });

            $dates->each(function ($date){
                $date->delete();
            });

            $venue->delete();
        });

        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);
        alert()->success('Venue has been deleted!', 'success!');
        return back()->withInput(['tab' => 'venues']);
   }
}
