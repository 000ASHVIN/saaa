<?php

namespace App\Repositories\WalletRepository;
use App\Billing\Invoice;
use App\Users\User;
use App\Wallet;

class WalletRepository
{
    private $wallet;
    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function payInvoice($user, $invoice)
    {
        $user = User::find($user);
        $invoice = Invoice::find($invoice);

        try{
            $money = $user->wallet->sub($invoice->balance, $invoice);
        }catch (Exception $exception){
            return $exception;
        }
        return $money;
    }

    public function displayMessage($message)
    {
        switch ($message) {
            case 'full_payment';
                alert()->success('Thank you for your payment', 'Thank you');
                return back();
                break;

            case 'partial_payment';
                alert()->success('Thank you for your payment', 'Thank you');
                return back();
                break;

            case 'declined';
                alert()->warning('You have Insufficient funds', 'Warning');
                return back();
                break;

            default : {
                alert()->warning('Whoops, looks like something went wrong', 'Warning');
                return back();
            }
        }
    }


}