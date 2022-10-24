<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\EventRepository;
use App\AppEvents\Webinar;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SyncEvent;

class AdminEventWebinarController extends Controller
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function store(Request $request, $event)
    {
        $this->validate($request, [
            'url' => 'required|url',
            'code' => 'required'
        ]);

        $event = $this->eventRepository->findBySlug($event);
        $venue = $event->venues->where('type', 'online')->first();

        if($venue) {
            foreach ($venue->pricings as $pricing){
                DB::transaction(function () use($request, $pricing){
                    $webinar = new Webinar($request->only(['url', 'code', 'passcode']));
                    $pricing->webinars()->save($webinar);
                });
            }
            $sync_event = new SyncEvent();
            $sync_event->sync_event($event);
            alert()->success('Your Webinar has been created', 'Success!');
        }
        else {
            alert()->error('No online venues available. There should be at least one online venue available to add a webinar.', 'Error!');
        }

        return back()->withInput(['tab' => 'webinars']);
   }

    public function update(Request $request, $id)
    {
        $webinar = Webinar::find($id);
        $webinar->update($request->all());

        if($webinar->pricing && $webinar->pricing->event) {
            $event = $webinar->pricing->event;
            $sync_event = new SyncEvent();
            $sync_event->sync_event($event);
        }
        
        alert()->success('Your webinar has been updated', 'Success!');
        return back()->withInput(['tab' => 'webinars']);
   }

    public function destroy($id)
    {
        $webinar = Webinar::find($id);
        $event = null;
        if($webinar->pricing && $webinar->pricing->event) {
            $event = $webinar->pricing->event;
        }
        $webinar->delete();

        if($event) {
            $sync_event = new SyncEvent();
            $sync_event->sync_event($event);
        }
        alert()->success('Your webinar has been deleted', 'Success!');
        return back()->withInput(['tab' => 'webinars']);
   }
}
