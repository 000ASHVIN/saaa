<?php

namespace App\Http\Controllers;

use App\AppEvents\Venue;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class CalendarController extends Controller
{
    public function index()
    {
        $venues = Venue::with('events','dates')->has('events')->has('dates')->get()->reject(function ($venue){
            if (! $venue->events->first()->is_active && $venue->events->first()->reference_id==null){
                return $venue;
            }
        });

        foreach ($venues as $venue){
            foreach ($venue->dates->where('is_active',1) as $date){
                if ($venue->type == 'face-to-face'){
                    $color = '#173175';
                }else{
                    $color = 'green';
                }
                if($venue->events->first()->reference_id == null){
                $event = Calendar::event(
                    $venue->events->first()->name, //event title
                    true, //full day event?
                    new \DateTime($date->date), //start time (you can also use Carbon instead of DateTime)
                    new \DateTime($date->date), //end time (you can also use Carbon instead of DateTime)
                    $venue->id, //optionally, you can specify an event ID
                    [
                        'url' => ($venue->events->first()->is_redirect? $venue->events->first()->redirect_url : route('events.show', $venue->events->first()->slug))
                    ]
                );
                $calendar = Calendar::addEvent($event, ['color' => $color]); //add an array with addEvents
               }
            }
        }
        
        $calendar->setOptions([ //set fullcalendar options
            'firstDay' => 1
        ]);

        return view('calendar.index', compact('calendar'));
    }

}
