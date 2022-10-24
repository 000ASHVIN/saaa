<?php

namespace App\Http\Controllers\Admin;

use App\CustomDebitOrders;
use App\CustomEftPayments;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        $start_this_year = Carbon::now()->startOfYear();
        $end_this_year = Carbon::now()->endOfYear();

        $users = User::with('subscriptions','subscriptions.plan')->where('created_at', '>=', $start_this_year)
                    ->where('created_at', '<=', $end_this_year)
                    ->get();

        $filtered = $users->filter(function($user) {
            $user->month = $user->created_at->format('M');
            return $user;
        });

        $months = $filtered->groupBy('month');

        $old_users_months = User::where('created_at', '>=', Carbon::now()->startOfYear()->subYear(1)->firstOfMonth())
            ->where('created_at', '<=', Carbon::now()->endOfYear()->subYear(1)->firstOfMonth())
            ->get()->filter(function($user) {
                $user->month = $user->created_at->format('M');
                return $user;
            })->groupBy('month');

        // Grab all the users that have joined in the past month.
        $users = User::where('created_at', '>=', Carbon::now()->firstOfMonth()->startOfDay())
                    ->where('created_at', '<=', Carbon::now()->lastOfMonth()->endOfDay())
                    ->get()
                    ->sortByDesc('id');

        $active = User::all()->reject(function ($user){
            if (! $user->isOnline()){
                return $user;
            }
        });
        return view('admin.dashboard', compact('users', 'months', 'old_users_months', 'active'));
    }

    public function custom_payments_selected()
    {
        $debits = CustomDebitOrders::count();
        $eft = CustomEftPayments::count();
        return view('admin.custom_payments.index', compact('eft', 'debits'));
    }

    public function export_custom_payments_selected(Request $request)
    {
        if ($request->list == 'eft'){
            $efts = CustomEftPayments::all();
            Excel::create('EFT Payments', function($excel) use ($efts) {


                   $excel->sheet('Exports', function($sheet) use($efts) {
                       $sheet->appendRow([
                           'User_id',
                           'First Name',
                           'Last Name',
                           'ID Number',
                           'Cell',
                           'Email',
                           'Date',
                       ]);
                       foreach ($efts as $eft){
                           $sheet->appendRow([
                               $eft->user_id,
                               $eft->first_name,
                               $eft->last_name,
                               $eft->id_number,
                               $eft->cell,
                               $eft->email,
                               date_format(Carbon::parse($eft->date), 'd F Y'),
                           ]);
                       }
                   });


            })->setFileName('EFT Payments')->export('csv');
        }else
            $debits = CustomDebitOrders::all();
            $file_name = sprintf("7923_%d_%s", Carbon::now()->format('dmy'), rand(100, 999));

        Excel::load(public_path('template/stratcol.xls'), function($reader) use($debits)
        {
            $reader->sheet('Sheet1',function($sheet) use ($debits)
            {
                foreach ($debits as $debit) {

                    if ($debit->user){

                        if($debit->type == 'savings'){
                            $debit->type = '2';
                        }elseif ($debit->type == 'cheque'){
                            $debit->type = '1';
                        }elseif ($debit->type == 'transmission'){
                            $debit->type = '3';
                        }else{
                            $debit->type = '0';
                        }

                        $sheet->appendRow([
                            '',
                            $debit->user->invoices->where('type','subscription')->first()->reference,
                            $debit->user->last_name,
                            '',
                            str_replace("-", " ", $debit->user->cell),
                            $debit->first_name.' '.$debit->last_name,
                            $debit->bank,
                            $debit->branch_name,
                            $debit->branch_code,
                            $debit->id_number,
                            $debit->number,
                            $debit->type,
                            '',
                            '',
                            '7923',
                            Carbon::parse($debit->start_date)->format('d.m.y'),
                            $debit->amount,
                            '',
                            'm',
                            $debit->billable_date,
                            'yes',
                            '9',
                            Carbon::parse($debit->final_date)->format('d.m.y')
                        ]);
                    }
                }
            });
        })->setFileName($file_name)->export('csv');
    }
}
