<?php

namespace App\Jobs;

use Illuminate\Mail\Mailer;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\InteractsWithQueue;

class NotifyClientAboutPTPArrangement extends Job implements SelfHandling
{
    use InteractsWithQueue;
    private $invoice;

    /**
     * Create a new job instance.
     *
     * @param $invoice
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $user = $this->invoice->user;
        $invoice = $this->invoice;

        if(sendMailOrNot($user, 'accounts.payment_arrangement')) {
        $mailer->later('120', 'emails.accounts.payment_arrangement', ['invoice' => $invoice], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($user->email)->cc(config('app.email'))
            ->subject('Payment Arrangement');
        });
        }
    }
}
