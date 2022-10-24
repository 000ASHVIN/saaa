<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Users\User;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var User
     */
    private $user;

    /**
     * Create a new job instance.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        if(sendMailOrNot($user, 'welcome')) {
        $mailer->later('300','emails.welcome', ['user' => $this->user ], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($user->email, $user->first_name.' '.$user->last_name)->subject('Welcome to '.config('app.name'));
        });
        }
    }
}
