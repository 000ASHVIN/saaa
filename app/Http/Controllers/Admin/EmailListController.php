<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Ticket;
use App\Billing\Invoice;
use App\Users\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class EmailListController extends Controller
{

    public function index()
    {
        return view('admin.exports.email_lists');
    }
    public function store_order_members()
    {
//        $invoices = Invoice::with('user')->whereType('store')->get();
        $user = User::has('invoices')->get();
        $data = $user->filter(function ($user){
            if ($user->invoices){
                return $user;
            }
        });
//        $users = collect();

//        $invoices->each(function ($invoice) use($users) {
//            if($invoice->user) {
//                if(! $users->contains($invoice->user))
//                    $users->push($invoice->user);
//            }
//        });

        $fileName = 'Purchased Anything';
        $this->ExportToExcel($data, $fileName);
    }

    public function webinar_tickets()
    {
        $tickets = Ticket::with('user')->where('name', 'Online admission')->get();
        $users = collect();

        $tickets->each(function ($ticket) use($users) {
            if($ticket->user) {
                if(! $users->contains($ticket->user))
                    $users->push($ticket->user);
            }
        });

        $fileName = 'Webinar Users';
        $this->ExportToExcel($users, $fileName);
    }

    public function cpd_members_with_invoice()
    {
        $users = collect();
        $invoices = Invoice::with('user')->where('type', 'subscription')->get();
        $invoices->each(function ($invoice) use($users) {
            if($invoice->user) {
                if(! $users->contains($invoice->user))
                    $users->push($invoice->user);
            }
        });
        $fileName = 'All CPD Subs with Invoice';
        $this->ExportToExcel($users, $fileName);
    }


    public function ExportToExcel($users, $fileName)
    {
        Excel::create($fileName, function ($excel) use ($users) {
            $excel->sheet('Sheet 1', function ($sheet) use ($users) {
                $sheet->appendRow([
                    'Name',
                    'ID Number',
                    'Email',
                    'Cell',
                    'Designation',
                    'Subscription'
                ]);

                foreach ($users as $user){
                    $sheet->appendRow([
                        $user->first_name.' '.$user->last_name,
                        ($user->id_number ? : "None"),
                        $user->email,
                        ($user->cell ? : "None"),
                        ($user->profile->position ? : "None"),
                        ($user->subscribed('cpd') ? $user->subscription('cpd')->plan->name : "None")
                    ]);
                }
            });
        })->export('xls');
    }
}
