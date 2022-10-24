<?php
/**
 * Created by PhpStorm.
 * User: Tiaan-Pc
 * Date: 11/7/2018
 * Time: 3:33 PM
 */

namespace App\Repositories\ThreeDsRepository;


use App\Card;
use App\Peach;

class ThreeDsRepository
{
    private $peach;
    public function __construct(Peach $peach)
    {
        $this->peach = $peach;
    }

    public function handleThreeDs($request)
    {
        $payment = $this->peach->fetchPayment($request->id);

        if(! Card::where('token', $payment->registrationId)->exists() && $payment->successful()) {
            $card = new Card([
                'token' => $payment->registrationId,
                'brand' => $payment->paymentBrand,
                'number' => $payment->card['bin'] . '******' . $payment->card['last4Digits'],
                'exp_month' => $payment->card['expiryMonth'],
                'exp_year' => $payment->card['expiryYear']
            ]);

            auth()->user()->cards()->save($card);

            if(count(auth()->user()->cards) == 1) {
                auth()->user()->update([
                    'primary_card' => $card->id
                ]);
            }

            alert()->success('Credit card added successfully.', 'Success');
        } else {
            alert()->error('Credit card already added or invalid.', 'Could not save credit card');
        }
    }

    public function handleThreeDsPayment($request)
    {
        
        $payment = $this->peach->fetchPayment($request->id);
        return $payment;

    }

}