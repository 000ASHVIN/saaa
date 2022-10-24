<?php

namespace App\Http\Controllers\Admin;

use App\Note;
use App\Subscriptions\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class MySaleController extends Controller
{
    public function index()
    {
        $events = Note::with('invoice')->where('type', 'event_registration')
            ->where('logged_by', auth()->user()->first_name." ".auth()->user()->last_name)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth()->startOfDay(), Carbon::now()->endOfMonth()->endOfDay()])->get();

        $cpds = Note::whereIn('type', ['subscription_upgrade', 'subscription_upgrade_procedure', 'new_subscription', 'recurring_subscription'])
            ->where('logged_by', auth()->user()->first_name." ".auth()->user()->last_name)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth()->startOfDay(), Carbon::now()->endOfMonth()->endOfDay()])->get();

        return view('admin.sales.index', compact('events', 'cpds', 'months'));
    }

    public function get_event_registrations(Request $request)
    {
        $data = Note::with('invoice')->where('type', 'event_registration')
            ->where('logged_by', auth()->user()->first_name." ".auth()->user()->last_name)
            ->whereBetween('created_at', [Carbon::parse($request->from)->startOfDay(), Carbon::parse($request->to)->endOfDay()])->get();

        if (count($data)){
            $notes = $data->filter(function ($event){
                if ($event->order && $event->order->ticket ){
                    return $event;
                }elseif ($event->invoice && $event->invoice->ticket){
                    return $event;
                }
            });

            Excel::create('My Sales Event Registrations from '.$request->from." - ".$request->to, function($excel) use ($notes) {
                foreach ($notes->groupBy('event') as $key => $value) {
                    $excel->sheet(str_limit(preg_replace('/[^A-Za-z0-9\-]/', '', ucfirst($key)), 20), function($sheet) use ($value) {
                        $sheet->appendRow(['Date', 'Member', 'Invoice', 'Status', 'Total', 'Balance', 'Event', 'Venue']);
                        foreach ($value as $data){
                            $sheet->appendRow([
                                date_format(Carbon::parse($data->created_at), 'd F Y'),
                                $data->user->id,
                                ucwords($data->user->first_name.' '.$data->user->last_name),
                                ($data->invoice ? $data->invoice->reference : "-"),
                                ($data->order ? $data->order->reference : "-"),
                                ($data->invoice ? $data->invoice->status : ($data->order ? $data->order->status : "-")),
                                ($data->commision_claimed ? "Yes" : "No"),
                                ($data->invoice ? $data->invoice->transactions->where('type', 'debit')->sum('amount') : ($data->order ? $data->order->total : "-")),
                                ($data->invoice ? $data->invoice->transactions->where('type', 'debit')->sum('amount') - $data->invoice->transactions->where('type', 'credits')->sum('amount') : ($data->order ? $data->order->balance : "-")),
                                ($data->invoice ? $data->invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : ($data->order ? $data->order->discount : "-")),
                                ($data->invoice ? $data->invoice->paid : ($data->order ? $data->order->paid : "-")),
                                ($data->order ? $data->order->ticket->event->name : ($data->invoice->ticket ? $data->invoice->ticket->event->name : "")),
                                ($data->order ? $data->order->ticket->venue->name : ($data->invoice->ticket ? $data->invoice->ticket->venue->name : "")),
                            ]);
                        }
                    });
                }
            })->export('xls');
        }else{
            alert()->error('No entries found for the date range specified', 'Please try again');
            return back();
        }
    }

    public function get_cpd_subscription_registrations(Request $request)
    {
        $cpds = Note::whereIn('type', ['subscription_upgrade', 'subscription_upgrade_procedure', 'new_subscription', 'recurring_subscription'])
            ->where('logged_by', auth()->user()->first_name." ".auth()->user()->last_name)
            ->whereBetween('created_at', [Carbon::parse($request->from)->startOfDay(), Carbon::parse($request->to)->endOfDay()])->get();

        if (count($cpds)){
            Excel::create('My Sales CPD Subscription Registrations from '.$request->from." - ".$request->to, function($excel) use ($cpds) {
                foreach ($cpds->groupBy('type') as $key => $value) {
                    $excel->sheet(str_replace('_', ' ', ucfirst($key)), function($sheet) use ($value) {
                        $sheet->appendRow(['Date', 'Member', 'Invoice', 'Status', 'Total', 'Balance', 'Old Subscription', 'New Subscription']);
                        foreach ($value as $data){

                            if ($data->upgrade)
                                if($data->invoice){
                                $sheet->appendRow([
                                    Carbon::parse($data['created_at'])->format('d-m-Y'),
                                    $data->invoice->user->first_name." ".$data->invoice->user->last_name,
                                    $data->invoice->reference,
                                    $data->invoice->status,
                                    $data->invoice->total,
                                    money_format('%.2n', $data->invoice->total - $data->invoice->transactions->where('type', 'credit')->sum('amount')),
                                    Plan::find($data->upgrade->old_subscription_package)->name." ". Plan::find($data->upgrade->old_subscription_package)->interval,
                                    Plan::find($data->upgrade->new_subscription_package)->name." ".Plan::find($data->upgrade->new_subscription_package)->interval
                                ]);
                                }else{
                                    $sheet->appendRow([
                                        Carbon::parse($data['created_at'])->format('d-m-Y'),
                                        $data->user->first_name." ".$data->user->last_name,
                                        '-',
                                        '-',
                                        '-',
                                        '-',
                                        ' - ',
                                        $data->user->subscribed('cpd')? $data->user->subscription('cpd')->plan->name : "No Subscription Plan"
                                    ]);
                                }
                            else{
                                if ($data->invoice){
                                    $sheet->appendRow([
                                        Carbon::parse($data['created_at'])->format('d-m-Y'),
                                        $data->invoice->user->first_name." ".$data->invoice->user->last_name,
                                        $data->invoice->reference,
                                        $data->invoice->status,
                                        $data->invoice->total,
                                        money_format('%.2n', $data->invoice->total - $data->invoice->transactions->where('type', 'credit')->sum('amount')),
                                        ' - ',
                                        $data->user->subscribed('cpd')? $data->user->subscription('cpd')->plan->name : "No Subscription Plan"
                                    ]);
                                }else{
                                    $sheet->appendRow([
                                        Carbon::parse($data['created_at'])->format('d-m-Y'),
                                        $data->user->first_name." ".$data->user->last_name,
                                        '-',
                                        '-',
                                        '-',
                                        '-',
                                        ' - ',
                                        $data->user->subscribed('cpd')? $data->user->subscription('cpd')->plan->name : "No Subscription Plan"
                                    ]);
                                }
                            }
                        }
                    });
                }
            })->export('xls');
        }else{
            alert()->error('No entries found for the date range specified', 'Please try again');
            return back();
        }

    }
}
