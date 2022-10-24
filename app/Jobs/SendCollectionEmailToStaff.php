<?php

namespace App\Jobs;
use Mail;
use App\Users\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class SendCollectionEmailToStaff extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

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
     * @return void
     */
    public function handle()
    {
        $user = $this->user;

        /* Notify that his account was suspended due to non payment. */
        $this->sendNotificationToUser($user);
    }

    private function sendNotificationToUser($user)
    {
        if(sendMailOrNot($user, 'accounts.suspended')) {
        Mail::send('emails.accounts.suspended', ['user' => $user], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($user->email)->subject('Account suspension for '. $user->first_name.' '.$user->last_name);
        });
        }
    }
}
