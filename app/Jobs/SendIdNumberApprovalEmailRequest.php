<?php

namespace App\Jobs;

use App\Users\User;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendIdNumberApprovalEmailRequest extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var User
     */
    private $user;
    private $new_id_number;

    /**
     * Create a new job instance.
     * @param User $user
     */
    public function __construct(User $user, $data)
    {
        $this->user = $user;
        $this->new_id_number = $data->new_id_number;
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
        $mailer->later('120', 'emails.change.id_number', ['user' => $this->user, 'new_id_number' => $this->new_id_number], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.to_email'))->subject('Please change my ID number: ' . $user->first_name . ' ' . $user->last_name);
        });
    }
}
