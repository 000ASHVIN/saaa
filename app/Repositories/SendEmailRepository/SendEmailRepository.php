<?php

namespace App\Repositories\SendEmailRepository;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailRepository implements ShouldQueue
{
    public function sendEmail($user, $view, $subject, $type)
    {
        Mail::later('120', $view, ['user' => $user], function ($message) use ($user, $subject, $type) {
            $message->from(config('app.email'), config('app.name'));
            $message->to($user->email);
            if ($user->billing_email_address) {
                $message->cc($user->billing_email_address, null);
            }
            $message->subject($subject);
            if ($type == 'store') {
                $message->bcc(config('app.email'), 'New Order');
            }
        });
    }
}
