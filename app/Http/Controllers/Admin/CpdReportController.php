<?php

namespace App\Http\Controllers\Admin;

use App\PreCPD;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class CpdReportController extends Controller
{
    public function pre_registration()
    {
        return view('admin.cpd_report.pre_registration');
    }

    public function api_pre_registrations()
    {

        $users = collect();
        $ToAddusers = DB::table('pre_cpds')->get();

        foreach ($ToAddusers as $adduser){
            $users->push($adduser);
        }

        return Datatables::of($users->unique('email'))
            ->editColumn('pi_cover', '{{ $pi_cover ? \'Yes\' : \'No\'}}')
            ->make(true);
    }

    // For Renewals
    public function cpd_renewals()
    {
         return view('admin.cpd_report.cpd_renewals');
    }

    // For Renewals
    public function api_cpd_renewals()
    {
        $users = collect();
        $ToAddusers = DB::table('renewals')->get();

        foreach ($ToAddusers as $adduser){
            $users->push($adduser);
        }

        return Datatables::of($users->unique('email'))
            ->editColumn('pi_cover', '{{ $pi_cover ? \'Yes\' : \'No\'}}')
            ->make(true);
    }


}
