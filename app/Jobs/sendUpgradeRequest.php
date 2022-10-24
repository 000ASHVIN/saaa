<?php

namespace App\Jobs;

use App\Users\User;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;

class sendUpgradeRequest extends Job implements SelfHandling
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
        $mailer->later('120', 'emails.upgrades.confirm', ['user' => $this->user], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.to_email'))->subject('We have received an upgrade/downgrade request for ' . $user->first_name . ' ' . $user->last_name);
        });
    }
}
