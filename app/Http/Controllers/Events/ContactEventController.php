<?php

namespace App\Http\Controllers\Events;

use App\Rep;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactEventController extends Controller
{
    public function store(Request $request, $event)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email_address' => 'required|email',
            'cell' => 'required|numeric'
        ]);

        $user = $request->only([
            'first_name',
            'last_name',
            'email_address',
            'cell'

        ]);

        $rep = Rep::nextAvailable();
        $topic = ucfirst(str_replace('-', ' ',$event));

        try {
            Mail::send('emails.leads.event_registration', ['rep' => $rep, 'user' => $user, 'topic' => $topic], function ($m) use ($user, $rep, $topic) {
                $m->from(config('app.email'), config('app.name'));
                $m->to($rep->email)->subject('New lead for event registration');
            });

            $rep->update(['emailedLast' => Carbon::now()]);
        } catch (\Exception $e) {
            alert()->error('Something went wrong, please contact us on 010 593 0466', 'Warning')->persistent('close');
            return back();
        }

        alert()->success('One of our agents will be in touch with you shortly', 'Success')->persistent('close');
        return back();
    }
}
