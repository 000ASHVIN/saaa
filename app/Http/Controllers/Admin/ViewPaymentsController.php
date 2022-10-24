<?php

namespace App\Http\Controllers\Admin;

use App\Billing\Invoice;
use App\Billing\Payment;
use App\Billing\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ViewPaymentsController extends Controller
{
    public function show_payments_per_day()
    {
        $transactions = Transaction::with('invoice', 'user')->where('tags', 'Payment')->paginate(15);

        return view('admin.payments.get_payments_per_day', compact('transactions'));
   }

    public function summary()
    {
        return view('admin.payments.summary');
    }

    public function postsummary(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $transactions = $this->getTransactionsforSummery($request, $from, $to, $type = null);
        $transactions = $transactions->whereIn('category',['subscription','store','event'])->get();
        if (count($transactions)){
            return view('admin.payments.summary_results', compact('transactions', 'from', 'to', 'type'));
        }else{
         alert()->error('We have no entries for that period', "No Entries Found");
            return redirect()->back();
        }
    }

    public function get_payments_per_day(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
        $type = $request->type;

        $transactions = $this->getTransactions($request, $from, $to , $type);

        $categories = $transactions->groupBy('category')->all();

        // Return the counts for all payment methods.
        return view('admin.payments.results', compact('transactions', 'categories', 'from', 'to', 'type'));
    }

    public function export(Request $request, $from, $to)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
        $type = $request->type;

        $transactions = $this->getTransactions($request, $from, $to, $type);

        Excel::create('Payments Report'.' '.$from->toFormattedDateString().' '.'-'.$to->toFormattedDateString(), function($excel) use($transactions) {
            $excel->setTitle('Payments Report');
            $excel->sheet('Payments', function($sheet) use($transactions) {
                $sheet->appendRow([
                    'ID',
                    'User ID',
                    'User Email',
                    'Invoice ID',
                    'Invoice Created At',
                    'Type',
                    'Display Type',
                    'Status',
                    'Invoice Status',
                    'Category',
                    // 'Amount',
                    'Invoice Total',
                    'Donation',
                    'Invoice Discount',
                    'Invoice Credit',
                    'Invoice VAT',
                    'Amount  Paid',
                    'Balance  due',
                    'Ref',
                    'Method',
                    'Description',
                    'Tags',
                    'date',
                    'Transaction Created At',
                ]);
                foreach ($transactions as $transaction) {
                    if($transaction->invoice){
                    $sheet->appendRow([
                        $transaction->id,
                        ($transaction->user) ? $transaction->user->id : "#",
                        ($transaction->user) ? $transaction->user->email : '#',
                        $transaction->invoice->id,
                        date_format($transaction->invoice->created_at, 'd F Y'),
                        $transaction->type,
                        $transaction->display_type,
                        $transaction->status,
                        $transaction->invoice->status,
                        $transaction->category,
                        // $transaction->amount,
                        ($transaction->invoice ? $transaction->invoice->total : '0'),
                        ($transaction->invoice ? $transaction->invoice->donation : '0'),
                        ($transaction->invoice ? $transaction->invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                        ($transaction->invoice ? (($transaction->invoice->status == 'credit noted' || $transaction->invoice->status == 'cancelled') ? $transaction->invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : 0) : '0'),
                        $transaction->invoice->vat_rate."%",
                        ($transaction->invoice ? (($transaction->invoice->status == 'paid') ? $transaction->invoice->transactions->where('type', 'debit')->sum('amount') - $transaction->invoice->transactions->where('tags', 'Discount')->sum('amount') : '0') : '0'),
                        ($transaction->invoice ? $transaction->invoice->transactions->where('type', 'debit')->sum('amount') - $transaction->invoice->transactions->where('type', 'credit')->sum('amount') : '0'),
                        $transaction->ref,
                        $transaction->method,
                        $transaction->description,
                        $transaction->tags,
                        date_format($transaction->date, 'd F Y'),
                        date_format($transaction->created_at, 'd F Y'),
                    ]);
                    }
                }
            });
        })->export('xls');
    }

    /**
     * @param $payments
     * @param $method
     * @internal param $eft
     */
    public function filterPaymets($payments, $method)
    {
        return $payments->filter(function ($payment) use($method) {
            if ($payment->method == $method)
                return $payment;
        });
    }

    /**
     * @param Request $request
     * @param $from
     * @param $to
     * @param $type
     * @return mixed
     */
    public function getTransactions(Request $request, $from, $to, $type)
    {
        if ($request->has('type') || $type) {
            $transactions = Transaction::with('invoice', 'invoice.items', 'user')
                ->whereBetween('date', [$from->startOfDay(), $to->endOfDay()])
                ->where('tags', 'Payment')
                ->where('method', $request->type ? : $type)
                ->get();
            return $transactions;
        } else
            $transactions = Transaction::with('invoice', 'invoice.items', 'user')
                ->whereBetween('date', [$from->startOfDay(), $to->endOfDay()])
                ->where('tags', 'Payment')
                ->get();
            return $transactions;
    }

     /**
     * @param Request $request
     * @param $from
     * @param $to
     * @param $type
     * @return mixed
     */
    public function getTransactionsforSummery(Request $request, $from, $to, $type)
    {
        if ($request->has('type') || $type) {
            $transactions = Transaction::with('invoice', 'invoice.items', 'user')
                ->whereBetween('date', [$from->startOfDay(), $to->endOfDay()])
                ->where('tags', 'Payment')
                ->where('method', $request->type ? : $type);
            return $transactions;
        } else
            $transactions = Transaction::with('invoice', 'invoice.items', 'user')
                ->whereBetween('date', [$from->startOfDay(), $to->endOfDay()])
                ->where('tags', 'Payment');
            return $transactions;
    }
}
