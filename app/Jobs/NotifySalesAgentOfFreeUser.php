<?php

namespace App\Jobs;

use App\Jobs\Job;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifySalesAgentOfFreeUser extends Job implements SelfHandling, ShouldQueue
{
    public $rep;
    public $user;

    public function __construct($rep, $user)
    {
        $this->rep = $rep;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rep = $this->rep;
        $user = $this->user;

        Mail::send('emails.leads.free_member', ['rep' => $rep, 'user' => $user], function ($m) use ($rep) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($rep->email)->subject('[Lead] - Free Profile');
        });
    }
}
