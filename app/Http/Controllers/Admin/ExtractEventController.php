<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class ExtractEventController extends Controller
{
    public function get_extract()
    {
        return view('admin.reports.events.stat.extract');
    }

    public function post_extract(Request $request) {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

        $eventStats = DB::table('events')
                        ->select('events.id','events.name','events.start_date', 
                                DB::raw("(SELECT count(id)  FROM `tickets` WHERE `event_id` = events.id and user_id not in (SELECT user_id FROM `invoices` WHERE id in (SELECT invoice_id FROM `items_lists` WHERE item_id in (SELECT id FROM `items` WHERE `name` = events.name ORDER BY `type` ASC)) and status='paid' and paid=1 and total>0)) as subscribed_user"),
                                DB::raw("(SELECT count(id)  FROM `tickets` WHERE `event_id` = events.id and user_id in (SELECT user_id FROM `invoices` WHERE id in (SELECT invoice_id FROM `items_lists` WHERE item_id in (SELECT id FROM `items` WHERE `name` = events.name ORDER BY `type`  ASC)) and status='paid' and paid=1 and total>0)) as paid_member"),
                                DB::raw("(SELECT GROUP_CONCAT(name) FROM `presenters` WHERE id in (SELECT presenter_id FROM `event_presenter` WHERE event_id=events.id)) as presenter,(SELECT group_concat(title) FROM `bodies` WHERE id in (SELECT body_id FROM `body_pricing` WHERE pricing_id in (SELECT id FROM `pricings` WHERE event_id=events.id and venue_id in (SELECT venue_id  FROM `event_venue` WHERE `event_id` = events.id)))) as professional_body")
                        )
                        ->leftJoin('event_venue', 'event_venue.event_id', '=', 'events.id')
                        ->leftJoin('dates', 'dates.venue_id', '=', 'event_venue.venue_id')
                        ->join('venues', 'venues.id', '=', 'event_venue.venue_id')
                        ->whereBetween('start_date',[$from,$to])
                        ->groupBy('events.id')
                        ->get();
        
        Excel::create('Event stat between '.date_format($from, 'Y-m-d').' - '.date_format($to, 'Y-m-d'), function($excel) use($eventStats) {
            $excel->sheet('Event Stat', function($sheet) use($eventStats){
                $sheet->appendRow([
                    'Name',
                    'Start Date',
                    'Subscribed User',
                    'Paid Member',
                    'Presenter',
                    'Professional Body',
                ]);

                foreach ($eventStats as $evetnStat) {
                    $sheet->appendRow([
                        $evetnStat->name,
                        date('Y-m-d', strtotime($evetnStat->start_date)),
                        $evetnStat->subscribed_user,
                        $evetnStat->paid_member,
                        $evetnStat->presenter,
                        $evetnStat->professional_body,
                    ]);
                }
            });
        })->export('xls');
    }
}
