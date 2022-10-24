<?php

namespace App\Jobs;

use App\AppEvents\Ticket;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignTicketToUserJob extends Job implements SelfHandling, ShouldQueue
{
    private $user;
    private $event;
    private $pricing;
    private $venue;
    private $date;

    public function __construct($user, $event, $pricing, $venue, $date)
    {
        $this->user = $user;
        $this->event = $event;
        $this->pricing = $pricing;
        $this->venue = $venue;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pricing = $this->pricing;
        $user = $this->user;
        $event = $this->event;
        $date = $this->date;
        $venue = $this->venue;

        $ticket = new Ticket;
        $ticket->code = str_random(20);
        $ticket->name = $pricing->name;
        $ticket->description = $pricing->description;
        $ticket->first_name = $user->first_name;
        $ticket->last_name = $user->last_name;
        $ticket->user_id = $user->id;
        $ticket->event_id = $event->id;
        $ticket->venue_id = $venue->id;
        $ticket->pricing_id = $pricing->id;
        $ticket->invoice_id = 0;
        $ticket->dietary_requirement_id = 0;
        $ticket->email = $user->email;

        $ticket->save();
        $ticket->dates()->save($date);
        $user->tickets()->save($ticket);
    }
}
