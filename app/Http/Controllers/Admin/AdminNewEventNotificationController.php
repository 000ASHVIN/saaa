<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AppEvents\EventNotification;
use App\AppEvents\Event;
use Carbon\Carbon;

class AdminNewEventNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = EventNotification::with('event')
            ->orderBy('schedule_date', 'desc')
            ->paginate(10);
        return view('admin.event_notification.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $events = Event::where('end_date', '>', Carbon::now())
            ->orderBy('start_date')
            ->get();
        return view('admin.event_notification.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'event' => 'required',
            'schedule_date' => 'date|after:today'
        ]);

        $event = Event::find($request->event);
        if(!$event) {
            alert()->error('Invalid event.', 'Error!');
            return redirect()->back()->withInput();
        }

        // Check date is already scheduled
        $date_event = EventNotification::where('schedule_date', $request->schedule_date)->first();
        if($date_event) {
            alert()->error('An event is already scheduled for the date you entered.', 'Error!');
            return redirect()->back()->withInput();
        }

        // Check if notifications already scheduled for the event
        if(!$event->notifications) {
            $event->notifications()->create([
                'status' => 'scheduled'
            ]);
        }
        else {
            alert()->error('Notification already scheduled for event.', 'Error!');
            return redirect()->back()->withInput();
        }
        
        $event->notifications()->update([
            'schedule_date' => $request->schedule_date
        ]);

        alert()->success('Notification scheduled successfully', 'Success!');
        return redirect()->route('admin.events.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $eventNotification = EventNotification::find($id);
        if(!$eventNotification) {
            alert()->error('Invalid event.', 'Error!');
            return redirect()->back();
        }

        $eventNotification->delete();
        alert()->success('Notification deleted successfully.', 'Success!');
        return redirect()->back();
    }
}
