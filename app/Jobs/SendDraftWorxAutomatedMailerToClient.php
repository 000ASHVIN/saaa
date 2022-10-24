<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Users\User;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDraftWorxAutomatedMailerToClient extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    private $data;

    /**
     * Create a new job instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $data = $this->data;
        if(sendMailOrNot($data, 'draftworx.welcome_email')) {
        $mailer->send('emails.draftworx.welcome_email', ['data' => $this->data], function ($m) use ($data) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($data['email'])->subject('Thank you for enquiring about Draftworx, via ' . config('app.name'));
        });
        }
    }
}
