<?php

namespace App\Http\Controllers\Admin;

use App\InvoiceOrder;
use App\Note;
use App\PerPeriodExportOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceOrderReportController extends Controller
{
    public function get_extract()
    {
        return view('admin.reports.InvoiceOrders.extract');
    }

    public function post_extract(Request $request)
    {
        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();

        $orders = InvoiceOrder::with('user')->whereBetween('created_at', [$from, $to])->get();
        Excel::create('Invoices from'.date_format($from, 'd F Y').' - '.date_format($to, 'd F Y'), function($excel) use($orders) {
            $excel->sheet('sheet', function($sheet) use ($orders){
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Cell',
                    'Date',
                    'reference',
                    'total',
                    'balance',
                    'type',
                    'Paid',
                    'Agent',
                    'Note ID'
                ]);

                foreach ($orders as $order){
                    $note = $order->note_id;
                    $agent = '';

                    if ($note){
                        $agent = Note::find($note)->logged_by;
                    }

                    $sheet->appendRow([
                        $order->user->first_name. ' '.$order->user->last_name,
                        strtolower($order->user->email),
                        $order->user->cell,
                        date_format($order->created_at, 'd F Y'),
                        $order->reference,
                        $order->total,
                        $order->balance,
                        $order->type,
                        ($order->paid ? "Yes": "No"),
                        ($agent ? : "N/A"),
                        $order->note_id
                    ]);
                }
            });
        })->export('xls');

        alert()->success('Your report was extracted', 'Success!');
        return redirect()->back();
    }

    public function getExtractFromDatePp()
    {
        return view('admin.reports.InvoiceOrders.p-period.index');
    }

    public function postExtractFromDatePp(Request $request)
    {
        list($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $orders) = $this->getData($request);
        return view('admin.reports.InvoiceOrders.p-period.results', compact('totalOrders', 'payments', 'discounts', 'cancellations', 'credits', 'from', 'to'));
    }

    public function export_results_outstanding_p_p(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        if ($from->diffInDays($to) >= 32){
            // Save entry
            PerPeriodExportOrder::create([
                'from' => $from,
                'to' => $to,
                'user_id' => auth()->user()->id,
                'processed' => false
            ]);

            alert()->success('Due to the size of your report, your report will be sent via email within the next 15 - 20 minutes with a download link.', 'Success!')->persistent('Thank you');
            return redirect()->route('admin.reports.payments.outstanding_orders_p_p');
        }else{
            list($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $orders) = $this->getData($request);
            $outstandingOrders = $orders->filter(function ($order){
                if ($order->balance){
                    return $order;
                }
            });
            $this->doMassiveExport($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $outstandingOrders, $orders)->export('xls');
            return redirect()->back();
        }
    }

    public function getData(Request $request)
    {
        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();
        $orders = InvoiceOrder::whereBetween('created_at', [$from, $to])->get();

        $data = collect();
        $orders->each(function ($invoice) use ($data) {
            foreach ($invoice->payments as $payment) {
                $data->push($payment);
            }
        });

        $totalOrders = $orders;
        $payments = $orders->where('status', 'paid');
        $discounts = $data->where('tags', 'discount');
        $cancellations = $orders->where('status', 'cancelled');
        $credits = $orders->where('status', 'paid');

        return array($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $orders);
    }

    public function doMassiveExport($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $outstandingOrders, $orders)
    {
        return Excel::create('Purchase Orders between ' . date_format($from, 'd F Y') . ' - ' . date_format($to, 'd F Y'), function ($excel) use ($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $outstandingOrders, $orders) {
            $excel->sheet('Total Invoiced', function ($sheet) use ($orders, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Status',
                    'Reference',
                    'Created At',
                    'Total',
                    'Order Type',
                    'Description'
                ]);

                foreach ($orders as $order) {
                    $sheet->appendRow([
                        $order->user->first_name,
                        $order->user->last_name,
                        $order->user->email,
                        $order->status,
                        $order->reference,
                        $order->created_at,
                        $order->total,
                        $order->type,
                        $order->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Total Payments', function ($sheet) use ($payments, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Status',
                    'Reference',
                    'Created At',
                    'Total',
                    'Order Type',
                    'Description'
                ]);

                foreach ($payments as $payment) {
                    $sheet->appendRow([
                        $payment->user->first_name,
                        $payment->user->last_name,
                        $payment->user->email,
                        $payment->status,
                        $payment->reference,
                        date_format($payment->created_at, 'd F Y'),
                        $payment->total,
                        $payment->type,
                        $payment->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Total Discounts', function ($sheet) use ($discounts, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Status',
                    'Reference',
                    'Created At',
                    'Total',
                    'Order Type',
                    'Description'
                ]);

                foreach ($discounts as $discount) {
                    $sheet->appendRow([
                        $discount->invoice_order->user->first_name,
                        $discount->invoice_order->user->last_name,
                        $discount->invoice_order->user->email,
                        $discount->invoice_order->status,
                        $discount->invoice_order->reference,
                        date_format($discount->created_at, 'd F Y'),
                        $discount->amount,
                        $discount->invoice_order->type,
                        $discount->invoice_order->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Total Cancellations', function ($sheet) use ($cancellations, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Status',
                    'Reference',
                    'Created At',
                    'Total',
                    'Order Type',
                    'Description'
                ]);

                foreach ($cancellations as $cancellation) {
                    $sheet->appendRow([
                        $cancellation->user->first_name,
                        $cancellation->user->last_name,
                        $cancellation->user->email,
                        $cancellation->status,
                        $cancellation->reference,
                        date_format($cancellation->created_at, 'd F Y'),
                        $cancellation->total,
                        $cancellation->type,
                        $cancellation->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Outstanding', function ($sheet) use ($outstandingOrders, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Status',
                    'Reference',
                    'Created At',
                    'Total',
                    'Order Type',
                    'Description'
                ]);

                foreach ($outstandingOrders->where('status', 'unpaid') as $order) {
                    $sheet->appendRow([
                        $order->user->first_name,
                        $order->user->last_name,
                        $order->user->email,
                        ($order->user->subscribed('cpd') ? $order->user->subscription('cpd')->plan->name : "None"),
                        $order->reference,
                        date_format($order->created_at, 'd F Y'),
                        $order->status,
                        $order->balance,
                        $order->type,
                        $order->items->first()->name
                    ]);
                }
            });
        });
    }
}
