<?php

namespace App\Console\Commands;
error_reporting(0);

use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Ticket;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use Illuminate\Console\Command;

class assignEventsToFreeMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:events-free-members';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We are assiging events to free members';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Only Free Members...
//        $users = User::whereDoesntHave('subscriptions')->get();

//         All System Users
        $users = User::all();
        $events = Event::WhereIn('id', [147])->get();
        $this->checkEvents($users, $events);
    }

    private function checkEvents($users, $events)
    {
        foreach ($users as $user) {
            if(count($user->tickets)) {
                $registered = collect([]);

                foreach ($user->tickets as $ticket) {
                    if($events->where('id', $ticket->event_id))
                        $registered->push($ticket->event_id);
                }

                $filtered = $events->reject(function($item) use ($registered)
                {
                    return in_array($item->id, $registered->toArray());
                })->unique('id');

                if(count($filtered))
                {
                    $this->registerUserForEvents($user, $filtered);
                }
            } else {
                $this->registerUserForEvents($user, $events->unique('id'));
            }
        }
    }

    private function registerUserForEvents($user, $events)
    {
        if (count($events)){
            $headers = ['ID', 'Event'];
            $toRegister = collect([]);

            foreach ($events as $event) {
                $venue = $event->venues()->where('type', 'online')->first();
                $date = $venue->dates->first();
                $pricing = Pricing::where('event_id', $event->id)->where('venue_id', $venue->id)->first();

                if(! $venue || ! $date || ! $pricing){
                    continue;
                } else {
                    $toRegister->push([
                        'id' => $event->id,
                        'name' => $event->name
                    ]);
                    $this->createTicket($user, $event, $pricing, $venue, $date);
                }
            }
        }
    }

    private function createTicket($user, $event, $pricing, $venue, $date)
    {
        $this->warn("Registering " . $user->first_name .' '. $user->last_name." for {$event->name}");

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
        $ticket->verifiable_cpd = true;

        $ticket->save();
        $ticket->dates()->save($date);
        $user->tickets()->save($ticket);
    }
}
