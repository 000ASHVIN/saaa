<?php

namespace App\Http\Controllers\Dashboard;

use App\Billing\Invoice;
use App\Card;
use App\Peach;
use App\Repositories\WalletRepository\WalletRepository;
use App\Users\User;
use App\Users\UserRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;

class WalletController extends Controller
{

    private $userRepository, $walletRepository, $peach;
    public function __construct(UserRepository $userRepository, WalletRepository $walletRepository, Peach $peach)
    {
        $this->userRepository = $userRepository;
        $this->walletRepository = $walletRepository;
        $this->peach = $peach;
    }


    public function index()
    {
        $user = $this->userRepository->find(auth()->user()->id);
        return view('dashboard.wallet.index', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param $userId
     * @return $this
     */
    public function store(Request $request, $userId)
    {
        $this->validate($request, ['amount' => 'required', 'method' => 'required', 'date' => 'required']);
        $amount = str_replace('R', '', $request['amount']);
        $date = $request->date;

        $user = User::find($userId);
        $user->wallet->add($amount, $request['method'], $date);

        alert()->success('The Wallet has been updated', 'Thank you');
        return redirect()->back()->withInput(['tab' => 'wallet']);
    }


    /*
     * This will allocate funds to Invoice.
     */
    public function payInvoice($user, $invoice)
    {
        $message = $this->walletRepository->payInvoice($user, $invoice);
        return $this->walletRepository->displayMessage($message);
    }

    /*
     * Export all transactions for givven wallet.
     */
    public function exportTransactions($user)
    {
        $user = User::find($user);
        $transactions = $user->wallet->transactions;

            Excel::create('Wallet Transactions', function($excel) use($transactions) {
            $excel->sheet('sheet', function($sheet) use ($transactions){
                $sheet->appendRow([
                    'Date',
                    'Reference',
                    'Type',
                    'Method',
                    'Category',
                    'Amount'
                ]);

                foreach ($transactions as $transaction){
                    $sheet->appendRow([
                        date_format($transaction->created_at, 'd F Y'),
                        $transaction->invoice_reference,
                        $transaction->type,
                        $transaction->method,
                        $transaction->category,
                        'R'.number_format($transaction->amount, 2)
                    ]);
                }
            });
        })->export('xls');
    }

    // Topup the wallet for the given member.
    public function topup(Request $request, $user, $card)
    {
        $this->validate($request, ['amount' => 'required']);
        $amount = str_replace('R', '', $request['amount']);

        $card = Card::find($card);
        $user = User::find($user);

        $payment = $this->peach->charge(
            $card->token,
            $amount,
            'wallet '.$user->wallet->reference,
            'Wallet Topup'
        );

        if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {
            $user->wallet->add($amount, 'cc');
            alert()->success('Your transaction was successful', 'Thank you');
            return back();
        } else {
            alert()->warning($payment['result']['description'], 'Warning');
            return back();
        }
    }

    public function withdrawal(Request $request, $user)
    {
        $this->validate($request, ['refund_amount' => 'required', 'reason' => 'required']);
        $amount = str_replace('R', '', $request['refund_amount']);

        $user = User::find($user);
        $data = $user->wallet->withdrawal($amount);

        if ($data == 'error'){
            alert()->warning('The member has Insufficient funds', 'warning');
        }else{
            alert()->success('Refunds has been processed', 'success');
        }
        return back();
    }
}
