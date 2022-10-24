<?php

namespace App\Http\Controllers\Admin;

use App\DebitOrder;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyAccountsAboutMigrationToPeach;
use App\Note;
use App\OTP;
use App\Repositories\DebitOrder\DebitOrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class DebitOrderController extends Controller
{
    private $debitOrderRepository;

    /**
     * DebitOrderController constructor.
     * @param DebitOrderRepository $debitOrderRepository
     */
    public function __construct(DebitOrderRepository $debitOrderRepository)
    {
        $this->debitOrderRepository = $debitOrderRepository;
    }

    public function index()
    {
        return view('admin.debit_orders.index');
    }

    public function search(Request $request)
    {
        return $this->debitOrderRepository->searchDebitOrders($request);
    }

    public function export_search(Request $request)
    {
        $debit_orders = $this->debitOrderRepository->export_searchDebitOrders($request);

        Excel::create('debit_orders', function ($excel) use ($debit_orders) {
            $excel->sheet('Debit Orders', function ($sheet) use ($debit_orders) {
                $sheet->appendRow([
                    'UserId',
                    'Email',
                    'Peach',
                    'Acc Holder',
                    'Date',
                    'has been contacted',
                    'Skip Next Debit',
                    'OTP',
                    'Number',
                    'Bank',
                    'Branch Name',
                    'Branch Code',
                ]);

                foreach ($debit_orders as $debit_order) {
                    if ($debit_order->user){
                        $sheet->appendRow([
                            $debit_order->user->id,
                            $debit_order->user->email,
                            ($debit_order->peach ? "Yes" : "No"),
                            ucwords($debit_order->account_holder),
                            $debit_order->billable_date,
                            ($debit_order->has_been_contacted ? "Yes" : "No"),
                            ($debit_order->skip_next_debit ? "Yes" : "No"),
                            $debit_order->otp,
                            $debit_order->number,
                            ucwords($debit_order->bank),
                            $debit_order->branch_name,
                            $debit_order->branch_code,
                        ]);
                    }
                }
            });

        })->store('xls', public_path('exports'));

        return response()->json([
            'file' => "/exports/debit_orders.xls"
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required',
            'bank' => 'required',
            'number' => 'required:numeric',
            'branch_code' => 'required:numeric',
            'branch_name' => 'required',
            'type' => 'required',
            'billable_date' => 'required',
            'peach' => 'required_with:otp',
            'otp' => 'required_if:peach,1',
            'id_number' => 'required_if:type_of_account,personal',
            'registration_number' => 'required_if:type_of_account,company'
        ]);

//        if ($request['skip_next_debit'] == true && auth()->user()->hasRole('super')){
//            $debit_order = $this->debitOrderRepository->findDebitOrder($id);
//            $note = new Note([
//                'type' => 'general',
//                'logged_by' => auth()->user()->first_name.' '.auth()->user()->last_name,
//                'description' => 'Skipping next debit order for '.$debit_order->billable_date.' '.date_format(Carbon::now()->addMonth(1), 'F Y')
//            ]);
//            $debit_order->update(['skip_next_debit' => $request['skip_next_debit'],]);
//            $debit_order->user->notes()->save($note);
//        }

        if ($validator->fails()) {
            return ['errors' => $validator->errors(), 422];
        }else{
            $debit_order = $this->debitOrderRepository->findDebitOrder($id);

            $note = new Note([
                'type' => 'debit_order_details_update',
                'logged_by' => auth()->user()->first_name.' '.auth()->user()->last_name,
                'description' => $request->note
            ]);

            $debit_order->user->notes()->save($note);
            $debit_order->update([
                'bank' => $request['bank'],
                'number' => $request['number'],
                'type' => $request['type'],
                'branch_name' => $request['branch_name'],
                'branch_code' => $request['branch_code'],
                'billable_date' => $request['billable_date'],
                'skip_next_debit' => $request['skip_next_debit'],
                'id_number' => $request['id_number'],
                'registration_number' => ($request['registration_number'] ? $request['registration_number']: $debit_order->user->debit->registration_number),
                'account_holder' => $request['account_holder'],
                'type_of_account' => $request['type_of_account'],
            ]);

            if ($request['peach'] == 1 && $debit_order->peach == false){
                if ($debit_order->user->wasInvoiceForSubscriptionThisMonth() || $debit_order->user->subscription('cpd')->plan->price == 0){
                    if ($request['otp'] != ''){

                        $otps = OTP::where('user_id', $debit_order->user->id)->get();
                        $valid = $otps->where('otp', $request->otp)->last();

                        if ($valid){
                            $date = $debit_order->getSubscriptionAndBillableDate();

                            $debit_order->user->subscription('cpd')->starts_at = $debit_order->user->subscription('cpd')->starts_at;
                            $debit_order->user->subscription('cpd')->ends_at = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($debit_order->user->subscription('cpd')->starts_at), 'F') . ' ' . Carbon::now()->year)->addMonth(1);
                            $debit_order->user->subscription('cpd')->save();

                            if ($debit_order->peach == false){
                                $this->dispatch((new NotifyAccountsAboutMigrationToPeach($debit_order->user)));
                            }

                            $debit_order->update(['billable_date' => $date, 'active' => true, 'peach' => true, 'otp' => $request->otp]);

                            $Donote = new Note([
                                'type' => 'general',
                                'logged_by' => auth()->user()->first_name.' '.auth()->user()->last_name,
                                'description' => 'Debit order has been migrated to Peach'
                            ]);
                            $debit_order->user->notes()->save($Donote);

                        }else{
                            return ['errors' => ['OTP' => 'Your OTP supplied is invalid!'], 422];
                        }
                    }else{
                        return response()->json(['message' => 'success'], 200);
                    }
                }else{

                    $date = date_format(Carbon::parse($debit_order->user->subscription('cpd')->ends_at), 'd F Y');
                    return ['errors' => [
                        'message' =>
                            'Unable to migrate to peach payments due to no subscription invoice has been generated for this month yet. - Next Invoice: '.$date.''
                    ], 422];
                }
            }
            return response()->json(['message' => 'success'], 200);
        }
    }

    public function export(Request $request)
    {
        if ($request->has('type')){
            $debit_orders = $this->debitOrderRepository->retreiveAllDebitOrders()->where('has_been_contacted', intval($request->type));
        }else{
            $debit_orders = $this->debitOrderRepository->retreiveAllDebitOrders();
        }

        $file_name = sprintf("7923_%d_%s", Carbon::now()->format('dmy'), rand(100, 999));

        Excel::load(public_path('template/stratcol.xls'), function($reader) use($debit_orders)
        {
            $reader->sheet('Sheet1',function($sheet) use ($debit_orders)
            {
            foreach ($debit_orders as $debit_order) {

                $debit_date = $this->getDebitDate($debit_order->billable_date);

                if ($debit_order->user){
                    if($debit_order->type == 'savings'){
                        $debit_order->type = '2';
                    }elseif ($debit_order->type == 'cheque'){
                        $debit_order->type = '1';
                    }elseif ($debit_order->type == 'transmission'){
                        $debit_order->type = '3';
                    }else{
                        $debit_order->type = '0';
                    }

                    if (count($debit_order->user->invoices->where('type','subscription'))){
                        $reference = $debit_order->user->invoices->where('type','subscription')->first()->reference;
                    }else{
                        $reference = $debit_order->user->invoices->first()->reference;
                    }

                    $sheet->appendRow([
                        '',
                        $reference,
                        $debit_order->user->last_name,
                        '',
                        str_replace("-", " ", $debit_order->user->cell),
                        $debit_order->user->first_name.' '.$debit_order->user->last_name,
                        $debit_order->bank,
                        $debit_order->branch_name,
                        $debit_order->branch_code,
                        str_replace(" ", "", $debit_order->user->id_number).':ID',
                        $debit_order->number,
                        $debit_order->type,
                        '',
                        '',
                        '7923',
                        $debit_date->format('d.m.y'),
                        ($debit_order->user->subscription('cpd')? $debit_order->user->subscription('cpd')->plan->price : "0.00"),
                        '',
                        'm',
                        $debit_order->billable_date,
                        'yes',
                        'continue',
                        ''
                    ]);
                }
            }
            });
        })->setFileName($file_name)->export('csv');
    }

    private function getDebitDate($billable_date)
    {
        if(empty($billable_date))
            return Carbon::now()->endOfMonth();

        if(Carbon::now()->day > $billable_date) {
            return Carbon::now()->addMonths(1)->day($billable_date);
        } else {
            return Carbon::now()->day($billable_date);
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->can('delete-debit-orders')){
            $this->debitOrderRepository->removeDebitOrder($id);
            alert()->success('D/O details was successfully removed from the system', 'Success!');
            return back();
        }else{
            alert()->error('You do not have permission to remove the debit order details', 'Error!');
            return back();
        }
    }
}
