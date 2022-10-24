<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Note;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailer;

class SendOTP extends Job implements SelfHandling, ShouldQueue
{
    private $user;
    private $otp;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $otp
     */
    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $user = $this->user;
        $otp = $this->otp;

        $mailer->send('emails.otp.view', ['user' => $this->user, 'otp' => $otp], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($user->email)->subject('Your OTP (One-time Pin)');
        });

        // Add note to profile after sending OTP via email.
        $note = new Note([
            'type' => 'general',
            'description' => 'Mail send to client with subject: Your OTP (One-time Pin) - '.$otp,
            'logged_by' => 'System',
        ]);
        $user->notes()->save($note);
    }
}
