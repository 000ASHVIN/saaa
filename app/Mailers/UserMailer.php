<?php

namespace App\Mailers;

use App\AppEvents\Event;
use App\AppEvents\Ticket;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserMailer extends Mailer
{
    /**
     * Sends a Welcome Email to User
     *
     * @param  User $user User to send Welcome Email to.
     */
    public function sendWelcomeMessageTo(User $user)
    {
        $subject = 'Welcome to ' . config('app.name');
        $view = 'emails.registration.welcome';
        $data = [
            'first_name' => $user->first_name,
            'type' => $user->subscription('cpd')->plan->name
        ];

        return $this->sendTo($user, $subject, $view, $data);
    }

    /**
     * Sends a Welcome Email to User
     *
     * @param  User $user User to send Welcome Email to.
     */
    public function sendNewMembershipWithPasswordTo(User $user, $password)
    {
        $subject = 'Welcome to SA ' . config('app.name');
        $view = 'mailers.member-imported-with-one-time-password';

        $data = [
            'first_name' => $user->first_name,
            'email' => $user->email,
            'password' => $password
        ];

        return $this->sendTo($user, $subject, $view, $data);
    }

    public function sendDebitOrderMailTo(User $user)
    {
        $subject = 'Debit order form required.';
        $view = 'mailers.debit_order_form_required';

        $data = [
            'first_name' => $user->first_name,
        ];

        return $this->sendTo($user, $subject, $view, $data);
    }

    /**
     * Sends a Welcome Email to User
     *
     * @param  User $user User to send Welcome Email to.
     */
    public function sendNewMembershipWithOneTimePasswordTo(User $user, $view)
    {
        $subject = 'New membership and one time password';
        $data = [
            'first_name' => $user->first_name,
            'email' => $user->email,
            'password' => $user->temp_password
        ];

        return $this->sendTo($user, $subject, $view, $data);
    }

    public function sendEventRegistrationConfirmationEmailTo(User $user, Ticket $ticket)
    {
        $subject = 'You have been registered for an event';
        $view = 'emails.events.registered';
        $dates = [];
        foreach ($ticket->venue->dates as $date) {
            $dates[] = Carbon::parse($date->date)->format('l, j F Y');
        }
        $data = [
            'first_name' => $user->first_name,
            'event_name' => $ticket->event->name,
            'venue_name' => $ticket->venue->name,
            'dates' => $dates,
            'event_link' => route('events.show', $ticket->event->slug)
        ];

        return $this->sendTo($user, $subject, $view, $data);
    }
}