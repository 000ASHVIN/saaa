<?php

namespace App\Http\Controllers\Admin;

use App\eventNotified;
use Artisan;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;
use Response;

class UpcomingEventReminderController extends Controller
{

    public function index()
    {
        $reports = eventNotified::all()->groupBy('event_name');
        return view('admin.event_reminders.index', compact('reports'));
    }

    public function runCommand(Request $request)
    {
        // Nothing to do here at the moment.
    }

    public function clear($key)
    {
        $notifications = eventNotified::where('event_name', $key);
        $notifications->delete();
        alert()->success('Your records has been deleted', 'Success');
        return back();
    }
}
