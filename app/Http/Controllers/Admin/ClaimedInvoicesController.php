<?php

namespace App\Http\Controllers\Admin;

use App\Billing\Invoice;
use App\Jobs\markInvoicesAsClaimed;
use App\Jobs\MarkInvoicesAsRefunded;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ClaimedInvoicesController extends Controller
{

    public function index()
    {
        return view('admin.claimed.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required'
        ]);

        if ($request->hasFile('file')){
            $file = Excel::load($request->file('file'), function($reader) {})->get();

            if(count($file) > 0) {
                $invoices = Invoice::wherein('reference', $file->toArray())->get();

                $failed = $file->count() - $invoices->count();
                $success = $invoices->count();

                if($request['type'] == 'claim'){
                    $this->dispatch(new markInvoicesAsClaimed($invoices, $request['claimed']));
                }else{
                    $this->dispatch(new MarkInvoicesAsRefunded($invoices));
                }

                if($success == 0) {
                    alert()->error("Something went wrong, we were not able to read any of the invoices that you have listed, please insure that your file is .XLS and that you have an 'reference' column", "Whoops!")->persistent('Close');
                    return redirect()->back();
                } else {
                    alert()->success("Your invoices has been processed, {$success} invoices was found successfully and {$failed} could not be found.", 'Success!')->persistent('Close');
                    return redirect()->back();
                }
            } else {
                alert()->error('No Invoices found in uploaded file, please try again', 'Error!')->persistent('Close');
                return redirect()->back();
            }
        }

        return back();
    }
}
