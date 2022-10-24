<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Mail\Mailer;
use App\Users\User;
use Illuminate\Support\Facades\Log;

class SendAnnualRenewalNotification extends Job implements SelfHandling
{

    protected $subscription;
    protected $rep;
    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subscription, $rep, $email)
    {
        $this->subscription = $subscription;
        $this->rep = $rep;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $rep = $this->rep;
        $subscription = $this->subscription;
        $user = $subscription->user;
        $plan = $subscription->plan;
        $users = User::where('deleted_at', null)->count();

        $subject = "";
        $template = '';
        if($this->email == 'before_2_weeks') {
            $subject = "Your CPD Subscription Anniversary";
            $template = "emails.upcoming_renewal.before_2_weeks";
        }
        else if($this->email == 'expiry_day') {
            $subject = "Reminder: Your CPD Subscription expires today!";
            $template = "emails.upcoming_renewal.expiry_day";
        }
        else {
            exit();
        }

        // Send reminder email
        $mailer->send($template, [
                'user' => $user,
                'plan' => $plan,
                'subscription' => $subscription,
                'users' => $users
            ], 
            function ($m) use ($user, $eventName, $subject, $rep) {
                $m->from(env('APP_EMAIL'), config('app.name'));
                $m->to($user->email)->subject($subject);
                // $m->to('jaykishan.utwani@tatvasoft.com')->subject($subject);

                if($rep) {
                    // $m->cc($rep->email);
                }
                
            }
        );

        Log::info('Annual renewal notification sent: '.$user->email.' - '.$template);
    }
}
