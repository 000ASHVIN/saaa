<?php

namespace App\Http\Controllers\Admin;

use App\Billing\Transaction;
use App\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class AdminWalletTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = WalletTransaction::orderBy('id', 'desc')->with('wallet', 'wallet.user')->paginate(20);
        return view('admin.wallet.index', compact('transactions'));
    }

    public function export(Request $request)
    {
        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();

        $transactions = WalletTransaction::whereBetween('created_at', [$from, $to])

            ->where(function ($query) use($request){
                if ($request['type']){
                    return $query->where('type', $request['type']);
                }
            })

            ->where(function ($query) use($request){
                if ($request['category']){
                    return $option = $query->where('category', $request['category']);
                }
            })->get();

        $this->extractTransactions($from, $to, $transactions);
        return back();
    }

    /**
     * @param $from
     * @param $to
     * @param $transactions
     */
    public function extractTransactions($from, $to, $transactions)
    {
        Excel::create('Wallet Transactions From ' . date_format($from, 'd F Y') . ' - ' . date_format($to, 'd F Y'), function ($excel) use ($transactions) {
            $excel->sheet('sheet', function ($sheet) use ($transactions) {
                $sheet->appendRow(['User', 'User ID', 'Wallet Reference', 'invoice_reference', 'Type', 'Method', 'Category', 'Date', 'Amount']);
                foreach ($transactions as $transaction) {
                    $sheet->appendRow([
                        $transaction->wallet->user->id,
                        $transaction->wallet->user->full_name(),
                        $transaction->wallet->reference,
                        $transaction->invoice_reference,
                        $transaction->type,
                        $transaction->method,
                        $transaction->category,
                        date_format($transaction->created_at, 'd F Y'),
                        $transaction->amount,
                    ]);
                }
            });
        })->export('xls');
    }
}
