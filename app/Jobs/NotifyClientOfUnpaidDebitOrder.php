<?php

namespace App\Jobs;

use App\DebitOrder;
use App\NumberValidator;
use App\Repositories\SmsRepository\SmsRepository;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifyClientOfUnpaidDebitOrder extends Job implements SelfHandling, ShouldQueue
{
    private $debitOrder;

    public function __construct(DebitOrder $debitOrder)
    {
        $this->debitOrder = $debitOrder;
    }

    /**
     * Execute the job.
     *
     * @param SmsRepository $smsRepository
     * @return void
     * @throws \libphonenumber\NumberParseException
     */
    public function handle(SmsRepository $smsRepository)
    {
        $debit = $this->debitOrder;

        // Send E-mail
        if(sendMailOrNot($debit->user, 'debit_orders.unpaid', 'payment')) {
        Mail::send('emails.debit_orders.unpaid', ['debit' => $debit], function ($m) use ($debit) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($debit->user->email)->subject('[ATTENTION] Your debit order has failed');
            $m->bcc(config('app.email'));
        });
        }
        $numbervValidator = new NumberValidator();
        $number = $numbervValidator->format($debit->user->cell);

        $data = [
            'message' => 'Dear ' . ucfirst(strtolower($debit->user->first_name)) . ', we were unable to debit your bank account. Please login to your ' . config('app.name') . ' account and update your billing information.',
            'number' => $number
        ];

        // Send SMS Notification
        $smsRepository->sendSms($data, $debit->user);
    }
}
