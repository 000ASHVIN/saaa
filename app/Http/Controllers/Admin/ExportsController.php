<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Event;
use App\Http\Controllers\Controller;
use App\NumberValidator;
use App\Subscriptions\Models\Plan;
use App\Users\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportsController extends Controller
{
	public function getEventRegistrations()
	{
		$events = Event::with(['venues', 'venues.dates'])->get();
        $plans = Plan::all();
		return view('admin.exports.event_registrations', compact('plans', 'events'));
	}

	public function postEventRegistrations(Request $request)
	{

	    $numberValidation = new NumberValidator();

		$this->validate($request, [
        	'plan' => 'required',
        	'attended' => 'required',
        	'event' => 'required'
    	]);

    	$format = $request['format'] ?: 'xlsx';

		// Get the selected event
		$event = Event::findBySlug($request->event);

        // Get the subscribers for the selected plan
        $plans = Plan::with('subscriptions', 'subscriptions.user', 'subscriptions.user.tickets', 'subscriptions.user.tickets.venue', 'subscriptions.user.tickets.invoice')->whereIn('id', $request->plan)->get();

		if($request->has('payment')) {
			$attendance = $this->getAttendance($event, $plans, $request->attended);
			$results = $this->getPayment($attendance, $request->payment);
		} else {
			$results = $this->getAttendance($event, $plans, $request->attended);
		}

        // Create excel csv from $results and return download...
        if (count($results)){
            Excel::create(($request->attended == 'yes') ? "Export of members that attended {$event->name}" : "Export of members that did not attend {$event->name}", function($excel) use($results, $plans, $request, $event, $format, $numberValidation) {
                if($format == 'xlsx')
                {
                    $venues = $results->groupBy('venue.name')->toArray();
                    foreach ($venues as $venue => $atendees) {
                        $excel->sheet(preg_replace('/[^a-zA-Z0-9 .]/', '', $venue), function($sheet) use($atendees, $numberValidation) {
                            $sheet->appendRow([
                                'First Name',
                                'Lasst Name',
                                'ID Number',
                                'Cell',
                                'Email Address',
                                'Venue',
                                'Subscription',
                            ]);

                            foreach ($atendees as $atendee){
                                $sheet->appendRow([
                                    $atendee['first_name'],
                                    $atendee['last_name'],
                                    $atendee['user']['id_number'],
                                    $numberValidation->format($atendee['user']['cell']),
                                    $atendee['email'],
                                    $atendee['venue']['name'],
                                    $atendee['package'],
                                ]);
                            }
                        });
                    }
                } else {
                    $excel->sheet('Exports', function($sheet) use($results) {
                        $sheet->fromArray($results->toArray());
                    });
                }

            })->export($format);
        }else{
		    alert()->error('no results found', 'No Results');
        }

		return back();
	}

	public function getAttendance($event, $plans, $attendance)
	{
		$attended = collect([]);
		$unattended = collect([]);

		foreach ($plans as $plan){
            foreach ($plan->subscriptions as $subscription) {
                if($subscription->user) {
                    if (count($subscription->user->tickets)) {
                        foreach ($subscription->user->tickets as $ticket) {
                            if($ticket->event_id == $event->id) {
                                $attended->push($ticket);
                            } else {
                                if(! $unattended->contains('email', $ticket->email))
                                    $unattended->push($ticket);
                            }
                        }
                    }
                }
            }
        }

		if($attendance == 'yes')
		{
			return $attended;
		}

		return $unattended;
	}

	public function getPayment($attendees, $payment_status)
	{
		$paid = collect([]);
		$unpaid = collect([]);

		foreach ($attendees as $attendee) {
			if ($attendee->invoice && $attendee->invoice->paid && $attendee->invoice->status == 'paid') {
				$paid->push($attendee);
			} else {
				$unpaid->push($attendee);
			}
		}

		if($payment_status == 'paid')
		{
			return $paid;
		} else {
			return $unpaid;
		}
	}
}