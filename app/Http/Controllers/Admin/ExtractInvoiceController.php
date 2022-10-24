<?php

namespace App\Http\Controllers\Admin;

use App\Billing\Invoice;
use App\PerPeriodExportInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class ExtractInvoiceController extends Controller
{
    public function getExtractFromDatePp()
    {
        return view('admin.reports.invoices.p-period.index');
    }

    public function postExtractFromDatePp(Request $request)
    {
        list($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $invoices) = $this->getData($request);
        return view('admin.reports.invoices.p-period.results', compact('totalInvoices', 'payments', 'discounts', 'cancellations', 'credits', 'from', 'to'));
    }

    public function export_results_outstanding_p_p(Request $request){

        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        if ($from->diffInDays($to) >= 30){
            PerPeriodExportInvoice::create([
                'from' => $from,
                'to' => $to,
                'user_id' => auth()->user()->id,
                'processed' => false
            ]);
            alert()->success('Due to the size of your report, your report will be sent via email within the next 15 - 20 minutes with a download link.', 'Success!')->persistent('Thank you');
            return redirect()->route('admin.reports.payments.outstanding_p_p');
        }else{
            list($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $invoices) = $this->getData($request);
            $outstandingInvoices = $invoices->filter(function ($invoice){
                if ($invoice->balance){
                    return $invoice;
                }
            });
            $this->doMassiveExport($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $outstandingInvoices, $invoices)->export('xls');
            return redirect()->back();
        }
    }

    public function extractFromDateToDate($from, $to)
    {
        /*
         * This method will extract invoices for given date with only
         * transactions for this invoices in the given date range!
         */
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();
        $invoices = Invoice::whereBetween('created_at', [$from,$to])->get();

        foreach ($invoices as $invoice){
            $tempTransactions = collect($invoice->transactions)->filter(function ($transaction) use($from, $to){
                if ($transaction->date >= $from && $transaction->date <= $to){
                    return $transaction;
                }
            });

            unset($invoice->transactions);
            $invoice->transactions = $tempTransactions;
        }

        $invoices = $invoices->groupBy(function ($invoice){
            return date_format(Carbon::parse($invoice->created_at), 'F - Y');
        });

        Excel::create('Invoices between '.date_format($from, 'Y-m-d').' - '.date_format($to, 'Y-m-d'), function($excel) use($invoices) {
            foreach ($invoices as $key => $value){
                $excel->sheet($key, function($sheet) use($value){
                    $sheet->appendRow([
                        'First Name',
                        'Last Name',
                        'Email',
                        'Subscription',
                        'Reference',
                        'Invoice Created At',
                        'Invoice Balance',
                        'Invoice Type'
                    ]);

                    foreach ($value as $invoice) {
                        $sheet->appendRow([
                            $invoice->user->first_name,
                            $invoice->user->last_name,
                            $invoice->user->email,
                            ($invoice->user->subscribed('cpd')? $invoice->user->subscription('cpd')->plan->name : "None"),
                            $invoice->reference,
                            date_format($invoice->created_at, 'Y-m-d'),
                            $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount'),
                            $invoice->type,
                        ]);
                    }
                });
            }
        })->export('xls');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getData(Request $request)
    {
        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();
        $invoices = Invoice::whereBetween('created_at', [$from, $to])->get();

        $data = collect();
        $invoices->each(function ($invoice) use ($data) {
            foreach ($invoice->transactions as $transaction) {
                $data->push($transaction);
            }
        });

        $totalInvoices = $data->where('display_type', 'Invoice');
        $payments = $data->where('display_type', 'Payment')->where('type', 'credit');
        $discounts = $data->where('tags', 'Discount');
        $cancellations = $data->where('tags', 'Cancellation')->where('type', 'credit');
        $credits = $data->where('type', 'credit');
        return array($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $invoices);
    }

    /**
     * @param $from
     * @param $to
     * @param $totalInvoices
     * @param $payments
     * @param $discounts
     * @param $cancellations
     * @param $credits
     * @param $invoices
     * @return mixed
     */
    public function doMassiveExport($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $outstandingInvoices, $invoices)
    {

        return Excel::create('Invoices between ' . date_format($from, 'Y-m-d') . ' - ' . date_format($to, 'Y-m-d'), function ($excel) use ($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $outstandingInvoices, $invoices) {
            $excel->sheet('Total Invoiced', function ($sheet) use ($invoices, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Created At',
                    'Total',
                    'Invoice Type',
                    'Description',
                    'Sales Agent',
                    'Payment Method'
                ]);

                foreach ($invoices as $invoice) {
                    $sheet->appendRow([
                        $invoice->user->first_name,
                        $invoice->user->last_name,
                        $invoice->user->email,
                        ($invoice->user->subscribed('cpd') ? $invoice->user->subscription('cpd')->plan->name : "None"),
                        $invoice->reference,
                        $invoice->created_at,
                        $invoice->transactions->where('type', 'debit')->sum('amount'),
                        $invoice->type,
                        $invoice->items->first()->name,
                        ($invoice->note ? $invoice->note->logged_by : "-"),
                        (count($invoice->transactions->where('tags', 'Payment')) ? $invoice->transactions->where('tags', 'Payment')->first()->method : "-")
                    ]);
                }
            });
            $excel->sheet('Total Payments', function ($sheet) use ($payments, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Date',
                    'Amount',
                    'Invoice Type',
                    'Description'
                ]);

                foreach ($payments as $payment) {
                    $sheet->appendRow([
                        $payment->invoice->user->first_name,
                        $payment->invoice->user->last_name,
                        $payment->invoice->user->email,
                        ($payment->invoice->user->subscribed('cpd') ? $payment->invoice->user->subscription('cpd')->plan->name : "None"),
                        $payment->invoice->reference,
                        date_format($payment->date, 'Y-m-d'),
                        $payment->amount,
                        $payment->invoice->type,
                        $payment->invoice->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Total Discounts', function ($sheet) use ($discounts, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Date',
                    'Amount',
                    'Invoice Type',
                    'Description'
                ]);

                foreach ($discounts as $discount) {
                    $sheet->appendRow([
                        $discount->invoice->user->first_name,
                        $discount->invoice->user->last_name,
                        $discount->invoice->user->email,
                        ($discount->invoice->user->subscribed('cpd') ? $discount->invoice->user->subscription('cpd')->plan->name : "None"),
                        $discount->invoice->reference,
                        date_format($discount->date, 'Y-m-d'),
                        $discount->amount,
                        $discount->invoice->type,
                        $discount->invoice->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Total Cancellations', function ($sheet) use ($cancellations, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Date',
                    'Amount',
                    'Invoice Type',
                    'Description'
                ]);

                foreach ($cancellations as $cancellation) {
                    $sheet->appendRow([
                        $cancellation->invoice->user->first_name,
                        $cancellation->invoice->user->last_name,
                        $cancellation->invoice->user->email,
                        ($cancellation->invoice->user->subscribed('cpd') ? $cancellation->invoice->user->subscription('cpd')->plan->name : "None"),
                        $cancellation->invoice->reference,
                        date_format($cancellation->date, 'Y-m-d'),
                        $cancellation->amount,
                        $cancellation->invoice->type,
                        $cancellation->invoice->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Total Credits', function ($sheet) use ($credits, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Date',
                    'Amount',
                    'Invoice Type',
                    'Description'
                ]);

                foreach ($credits as $credit) {
                    $sheet->appendRow([
                        $credit->invoice->user->first_name,
                        $credit->invoice->user->last_name,
                        $credit->invoice->user->email,
                        ($credit->invoice->user->subscribed('cpd') ? $credit->invoice->user->subscription('cpd')->plan->name : "None"),
                        $credit->date,
                        date_format($credit->date, 'Y-m-d'),
                        $credit->amount,
                        $credit->invoice->type,
                        $credit->invoice->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Outstanding', function ($sheet) use ($outstandingInvoices, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Created At',
                    'Status',
                    'Total',
                    'Type',
                    'Description'
                ]);

                foreach ($outstandingInvoices as $invoice) {
                    $sheet->appendRow([
                        $invoice->user->first_name,
                        $invoice->user->last_name,
                        $invoice->user->email,
                        ($invoice->user->subscribed('cpd') ? $invoice->user->subscription('cpd')->plan->name : "None"),
                        $invoice->reference,
                        date_format($invoice->created_at, 'Y-m-d'),
                        $invoice->status,
                        $invoice->balance,
                        $invoice->type,
                        $invoice->items->first()->name
                    ]);
                }
            });
        });
    }
}
