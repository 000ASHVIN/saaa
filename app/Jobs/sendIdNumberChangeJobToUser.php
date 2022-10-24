<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class sendIdNumberChangeJobToUser extends Job implements SelfHandling, ShouldQueue
{
    private $user;
    private $oldIdNumber;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $oldIdNumber
     */
    public function __construct($user, $oldIdNumber)
    {
        $this->user = $user;
        $this->oldIdNumber = $oldIdNumber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $oldIdNumber = $this->oldIdNumber;

        if(sendMailOrNot($user, 'change.id_address_change_to_user')) {
        Mail::send('emails.change.id_address_change_to_user', ['user' => $user, 'oldIdNumber' => $oldIdNumber ], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($user->email, $user->name.' '.$user->last_name)->subject('Your ID number has been changed');
        });
        }
    }
}
