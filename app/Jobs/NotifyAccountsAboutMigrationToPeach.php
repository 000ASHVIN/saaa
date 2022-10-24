<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAccountsAboutMigrationToPeach extends Job implements SelfHandling, ShouldQueue
{
    private $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        Mail::send('emails.accounts.migration', ['debit' => $user->debit], function ($m) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.to_email')->get('email_addresses.notifiable_email_accounts'))->subject('New debit order migration took place');
        });
    }
}
