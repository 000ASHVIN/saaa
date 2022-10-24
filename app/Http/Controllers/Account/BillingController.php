<?php

namespace App\Http\Controllers\Account;

use App\Card;
use App\Note;
use App\Peach;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreCreditCardRequest;
use App\Http\Requests\Account\CreditCardRequest;

class BillingController extends Controller
{
    private $peach;

    public function __construct(Peach $peach)
    {
        $this->peach = $peach;
    }

    /**
     * User Billing Information
     */
    public function index()
    {
        $user = auth()->user()->load('cards');

        return view('dashboard.billing.index', compact('user'));
    }

    public function store(StoreCreditCardRequest $request)
    {
        $auth = $this->peach->autorize(
            $request->number,
            $request->holder,
            $request->exp_month,
            $request->exp_year,
            $request->cvv,
            time() . '- Authorization',
            $request->return ?: '/account/billing/return'
        );

        return response()->json($auth, 200);
    }

    public function billingstore(CreditCardRequest $request)
    {
        $auth = $this->peach->autorizeandFetch(
            $request->number,
            $request->holder,
            $request->exp_month,
            $request->exp_year,
            $request->cvv,
            time() . '- Authorization',
            $request->return ?: '/account/billing/return',
            $request->amount
        );

        return response()->json($auth, 200);
    }

    public function primary()
    {
        auth()->user()->update([
            'primary_card' => request()->id
        ]);
    }

    public function remove()
    {
        $card = Card::find(request()->card_id);
        $this->peach->deleteToken($card->token);

        $note = new Note([
            'type' => 'general',
            'description' => 'Credit Card '.$card->number.' was removed from account. Actioned:'.auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'logged_by' => 'System',
        ]);
        $card->user->notes()->save($note);

        $card->delete();
        return back();
    }

    public function addCard(Request $request)
    {
        try {
            $payment = $this->peach->fetchPayment($request->id);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $details = json_decode($e->getResponse()->getBody()->getContents(), true);
            return response()->json($details, 422);
        }

        if(Card::where('token', $payment->registrationId)->exists()) {
            return response()->json([
                'errors' => [
                    'number' => 'Card already linked to different account, please contact us.'
                ]
            ], 422);
        }

        if($payment->successful()) {
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

            return response()->json([
                'card' => $card
            ], 200);
        }

        return response()->json([
            'number' => 'Could not save the provided credit card, please try another'
        ], 422);
    }

    public function return()
    {
        if(! request()->id) {
            return redirect()->route('dashboard.billing.index');
        }

        $payment = $this->peach->fetchPayment(request()->id);

        if(Card::where('token', $payment->registrationId)->exists()) {

            alert()->error("This card is already linked to another account");
            return redirect()->route('dashboard.billing.index');
        }

        if($payment->successful()) {
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

            alert()->success("Card added sucessfully");

            // Add Flash Success
            return redirect()->route('dashboard.billing.index');
        }

        alert()->error($payment->result['description']);

        return redirect()->route('dashboard.billing.index');
    }
}
