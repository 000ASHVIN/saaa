<?php

namespace App\Http\Controllers\Admin\Unsubscribe;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\DatatableRepository\DatatableRepository;
use App\Resubscribe;
use App\Unsubscribe;
use App\Users\Industry;
use Maatwebsite\Excel\Facades\Excel;

class UnsubscribeController extends Controller
{
    private $datatableRepository;

    public function __construct(DatatableRepository $datatableRepository)
    {
        $this->datatableRepository = $datatableRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.unsubscribers.index');
    }

    public function list_unsubscribers()
    {
        return $this->datatableRepository->list_unsubscribers();
    }

    public function export_report() {
        $unsubscribers = Unsubscribe::with('user')->get();
        
        Excel::create('Unsubscribers Report', function ($excel) use ($unsubscribers) {
            $excel->sheet('Unsubscribers Data', function ($sheet) use ($unsubscribers) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Contact Number',
                    'Reasons'
                ]);

                foreach ($unsubscribers as $unsubscriber) {
                    $reason = json_decode($unsubscriber->reason);
                    $reason = implode(', ', $reason);
                    $sheet->appendRow([
                        $unsubscriber->user? $unsubscriber->user->name : 'N/A',
                        $unsubscriber->email,
                        $unsubscriber->user? $unsubscriber->user->cell : 'N/A',
                        $reason
                    ]);
                }

            });
        })->export('xls');

        return back();
    }

    public function resubscriber()
    {
        return view('admin.unsubscribers.resubscriber');
    }

    public function list_resubscribers()
    {
        return $this->datatableRepository->list_resubscribers();
    }

    public function export_resubscriber_report() {
        $resubscribers = Resubscribe::with('user')->get();
        
        Excel::create('Resubscribers Report', function ($excel) use ($resubscribers) {
            $excel->sheet('Resubscribers Data', function ($sheet) use ($resubscribers) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Contact Number',
                    'Unsubscribed from all marketing emails',
                    'Subscribed Types'
                ]);

                foreach ($resubscribers as $resubscriber) {
                    $subscribed_types = "-";
                    if(!$resubscriber->unsubscribe_all) {
                        $subscribed_types = json_decode($resubscriber->subscribed_types);
                        $subscribed_types = implode(', ', $subscribed_types);
                    }

                    $sheet->appendRow([
                        $resubscriber->user? $resubscriber->user->name : 'N/A',
                        $resubscriber->email,
                        $resubscriber->user? $resubscriber->user->cell : 'N/A',
                        $resubscriber->unsubscribe_all ? 'Yes' : 'No',
                        $subscribed_types
                    ]);
                }

            });
        })->export('xls');

        return back();
    }
}
