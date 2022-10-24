<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Donation;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $donations = Donation::
            where('status', 1);

        if($request->full_name) {
            $donations->whereRaw('concat(first_name, " ", last_name) Like "%'.$request->full_name.'%"');
        }

        if($request->email) {
            $donations->where('email', '=', $request->email);
        }

        $from = '';
        $to = '';
        if($request->has('from') && $request->has('to')) {
            $from = Carbon::parse($request->from);
            $to = Carbon::parse($request->to);

            $donations = $donations
                                ->whereBetween('created_at', [$from, $to]);
        }
        $totalDonation = clone $donations;

        $donations = $donations->orderBy('created_at', 'desc')
            ->paginate(10);

        // Find totals
        $now = Carbon::now();
        $total_donations = $totalDonation
            ->sum('amount');
        
        $todays_donations = Donation::where('status', 1)
            ->whereBetween('created_at', [$now->startOfDay()->format('Y-m-d H:i:s'), $now->endOfDay()->format('Y-m-d H:i:s')])
            ->sum('amount');
        $todays_donations = $todays_donations?$todays_donations:0;

        return view('admin.donations.index', ['donations' => $donations, 'total_donations' => $total_donations, 'todays_donations' => $todays_donations, 'from' => $from, 'to' => $to]);
    }

    public function export(Request $request) {
        
        $donations = Donation::where('status', 1);

        if($request->has('from') && $request->has('to') && $request->from != '' && $request->to != '') {
            $from = Carbon::parse($request->from);
            $to = Carbon::parse($request->to);

            $donations = $donations->whereBetween('created_at', [$from, $to]);
        }
        $donations = $donations->orderBy('created_at', 'desc')->get();
        
        Excel::create('Donation Export', function ($excel) use ($donations) {
            $excel->sheet('Donations', function ($sheet) use ($donations) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Company',
                    'Cellphone',
                    'Amount',
                    'Status',
                    'Date Time'
                ]);
                
                foreach($donations as $donation) {
                    $sheet->appendRow([
                        $donation->first_name." ".$donation->last_name,
                        $donation->email,
                        $donation->company_name,
                        $donation->cell,
                        $donation->amount,
                        ($donation->status == 1 ? 'successful' : 'unsuccessful'),
                        $donation->created_at->format('d-m-Y H:i')
                    ]);
                }
            
            });
        })->export('xls');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }
}

