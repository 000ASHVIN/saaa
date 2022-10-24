<?php

namespace App\Jobs;

use App\Users\User;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailAddressApprovalEmailRequest extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $user;
    private $new_email_address;

    public function __construct(User $user, $data)
    {
        $this->new_email_address = $data->new_email_address;
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
        $mailer->later('120', 'emails.change.email_address', ['user' => $this->user, 'new_email_address' => $this->new_email_address], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.to_email'))->subject('Please change my email address: ' . $user->first_name . ' ' . $user->last_name);
        });
    }
}
