<?php

namespace App\Jobs;

use App\Jobs\Job;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEventConfirmation extends Job implements SelfHandling, ShouldQueue
{
    private $user;
    private $ticket;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $ticket)
    {
        $this->user = $user;
        $this->ticket = $ticket;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ticket = $this->ticket;
        $user = $this->user;

        $dates = [];
        foreach ($ticket->venue->dates as $date) {
            $dates[] = Carbon::parse($date->date)->format('l, j F Y');
        }

        $data = [
            'first_name' => $ticket->user->first_name,
            'event_name' => $ticket->event->name,
            'venue_name' => $ticket->venue->name,
            'dates' => $dates,
            'event_link' => route('events.show', $ticket->event->slug)
        ];

        // Send E-mail
        if(sendMailOrNot($user, 'events.registered')) {
        Mail::send('emails.events.registered', ['dates' => $dates, 'data' => $data], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($user->email)->subject('You have been registered for an event');
        });
        }
    }
}
