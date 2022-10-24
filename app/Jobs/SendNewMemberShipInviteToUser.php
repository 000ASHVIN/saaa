<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Mailers\Mailer;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendNewMemberShipInviteToUser extends Job implements SelfHandling, ShouldQueue
{
    public $newUser;

    /**
     * Create a new job instance.
     *
     * @param $newUser
     */
    public function __construct($newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->newUser;

        $data = [
            'first_name' => $user->first_name,
            'email' => $user->email,
            'password' => $user->temp_password
        ];

        Mail::send('mailers.member-imported-with-one-time-password', $data, function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.email'), $user->email)->subject('New membership and one time password');
        });
    }
}
