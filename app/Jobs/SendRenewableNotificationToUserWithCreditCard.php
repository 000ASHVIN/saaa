<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendRenewableNotificationToUserWithCreditCard extends Job implements SelfHandling, ShouldQueue
{
    private $view;
    private $user;
    private $subject;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $view, $subject)
    {
        $this->view = $view;
        $this->user = $user;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $view = $this->view;
        $user = $this->user;
        $subject = $this->subject;
        if(sendMailOrNot($user, $view)) {
        Mail::send($view, ['user' => $user], function ($m) use ($user, $subject) {
            $m->from(config('app.email'), config('app.name'));
            if ($user->billing_email_address){ 
                if(strpos($user->billing_email_address,"@") == true && strpos($user->billing_email_address," ") != true){
                    $m->cc(explode(",", str_replace(';', ',', $user->billing_email_address)), null);
                }
            }
            $m->to($user->email, $user->first_name)->subject($subject);
        });
        }
    }
}
