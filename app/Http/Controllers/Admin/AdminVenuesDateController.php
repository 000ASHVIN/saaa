<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SyncEvent;

class AdminVenuesDateController extends Controller
{
    public function store(Request $request, $venue)
    {
        $venue = Venue::find($venue);

        $venue->dates()->save(new Date([
            'date' => Carbon::parse($request->date),
            'is_active' => $request->is_active
        ]));

        $event = $venue->events->first();
        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);
        alert()->success('Your date has been created', 'Success!');
        return back()->withInput(['tab' => 'venues']);
    }

    public function update(Request $request, $event, $id)
    {
        $date = Date::find($id);
        $date->update($request->all());

        $event = Event::findBySlug($event);
        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);
        alert()->success('Your changes has been saved', 'Success!');
        return back()->withInput(['tab' => 'dates']);
    }
}
