<?php

namespace App\Jobs;

use App\NumberValidator;
use App\Repositories\SmsRepository\SmsRepository;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;

class PtpReminders extends Job implements SelfHandling, ShouldQueue
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
     * @param SmsRepository $smsRepository
     * @return void
     * @throws \libphonenumber\NumberParseException
     */
    public function handle(Mailer $mailer, SmsRepository $smsRepository)
    {
        $user = $this->invoice->user;
        $invoice = $this->invoice;
        $smsValidator = new NumberValidator();
        $amount = number_format($invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount'), 2, '.', '');

        if ($smsValidator->validate($user['cell']) == true) {
            $number = $smsValidator->format($user['cell']);

            $data = [
                'number' => $number,
                'message' => ucfirst(strtolower($user->first_name)) . ' ' . ucfirst(strtolower($user->last_name)) . ' Reminder: Your payment of R' . $amount . ' for outstanding invoice is due ' . $invoice->ptp_date . '. Please pay as arranged. ' . config('app.email'),
                'tag' => 'PTP',
            ];

            $smsRepository->sendSms($data, $user);

            if(sendMailOrNot($user, 'accounts.payment_arrangement_reminder')) {
            $mailer->send('emails.accounts.payment_arrangement_reminder', ['invoice' => $invoice], function ($m) use ($user) {
                $m->from(config('app.email'), config('app.name'));
                $m->to($user->email)->cc(config('app.to_email'))->subject('Reminder for payment arrangement');
            });
            }
        }
    }
}
