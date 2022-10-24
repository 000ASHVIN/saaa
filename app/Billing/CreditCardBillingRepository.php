<?php

namespace App\Billing;


use App\Users\User;
use App\Subscriptions\Models\Plan;
use App\Services\Billing\Mygate\Mygate;
use App\Services\Billing\Mygate\MyGateCard;
use App\Services\Billing\Mygate\Exceptions\PaymentFailedException;

class CreditCardBillingRepository
{
    /**
     * @var Mygate
     */
    private $gateway;

    public function __construct(Mygate $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @param User $user
     * @param Plan $plan
     * @param Invoice $invoice
     * @param $transaction_id
     * @param $pares
     * @param MyGateCard $card
     * @param bool $enrolled
     * @return bool
     */
    public function chargeSubscription(User $user, Plan $plan, Invoice $invoice, $transaction_id, $pares, MyGateCard $card, $enrolled)
    {
        if($enrolled){
            if($this->gateway->authenticate($transaction_id, $pares))
                return $this->gateway->charge($card, $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount'), $invoice->reference);
        } else {
            return $this->gateway->charge($card, $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount'), $invoice->reference, $transaction_id);
        }
    }

    public function chargeInvoice(Invoice $invoice, $transaction_id, $pares, MyGateCard $card, $enrolled)
    {
        if($enrolled){
            if($this->gateway->authenticate($transaction_id, $pares))
                return $this->gateway->charge($card, $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount'), $invoice->reference);
        } else {
            return $this->gateway->charge($card, $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount'), $invoice->reference, $transaction_id);
        }
    }
}