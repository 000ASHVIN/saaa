<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class sendEmailAddressChnageToUserJob extends Job implements SelfHandling, ShouldQueue
{
    private $oldEmailAddress;
    private $user;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $oldEmailAddress
     */
    public function __construct($user, $oldEmailAddress)
    {
        $this->user = $user;
        $this->oldEmailAddress = $oldEmailAddress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $oldEmailAddress = $this->oldEmailAddress;

        Mail::send('emails.change.email_address_change_to_user', ['user' => $user, 'oldEmail' => $oldEmailAddress ], function ($m) use ($user, $oldEmailAddress) {
            $m->from(config('app.email'), config('app.name'));
            $m->to([$oldEmailAddress, $user->email], $user->name.' '.$user->last_name)->subject('Email address changed');
        });
    }
}
