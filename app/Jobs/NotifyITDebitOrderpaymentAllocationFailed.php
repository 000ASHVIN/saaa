<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailer;
use Illuminate\Contracts\Bus\SelfHandling;

class NotifyITDebitOrderpaymentAllocationFailed extends Job implements SelfHandling, ShouldQueue
{
    private $debitOrder;

    /**
     * Create a new job instance.
     *
     * @param $debitOrder
     */
    public function __construct($debitOrder)
    {
        $this->debitOrder = $debitOrder;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $debit_order = $this->debitOrder;
        $mailer->send('emails.debit_orders.failed_payment_allocation', ['debit_order' => $debit_order], function ($m) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.to_email'))->subject('DO Payment Allocation failed');
        });
    }
}
