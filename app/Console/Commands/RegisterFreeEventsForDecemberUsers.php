<?php

namespace App\Console\Commands;

use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Ticket;
use App\Subscriptions\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RegisterFreeEventsForDecemberUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:eventsDecember';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will assign the free events to those who subscription started in December 2016';

    /**
     * Create a new command instance.
     *
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
        $events = $this->getEvents('free');
        $users = $this->getSubscribers();
        $this->checkEvents($users, $events);
        $this->info("the total subscribers is " .count($users));
    }

    public function getSubscribers()
    {
        $users = collect();
        $subscriptions = Subscription::with('user')->active()->where('created_at', 'LIKE', '2016-12-01%')->where('canceled_at', null)->get();
        $subscriptions->each(function ($subscription) use($users){
            if ($subscription->user->status != 'suspended'){
                $users->push($subscription->user);
            }
        });
        return $users;
    }

    private function getEvents($category = '')
    {
        $events = Event::freePregistrationEvents();

        if($category != '')
            $events->where('category', $category);

        $filtered = $events->get()->filter(function($event) {
            if(count($event->venues) == 1 && $event->venues->first()->type == 'online') {
                return $event;
            } elseif($event->end_date <= Carbon::now()) {
                return $event;
            }
        });

        return $filtered;
    }

    private function checkEvents($users, $events)
    {
        foreach ($users as $user) {
            if(count($user->tickets))
            {
                $registered = collect([]);

                foreach ($user->tickets as $ticket) {
                    if($events->where('id', $ticket->event_id))
                        $registered->push($ticket->event_id);
                }

                $filtered = $events->reject(function($item) use ($registered)
                {
                    return in_array($item->id, $registered->toArray());
                });

                if(count($filtered))
                {
                    $this->registerUserForEvents($user, $filtered);
                }
            } else {
                $this->registerUserForEvents($user, $events);
            }
        }
    }

    private function registerUserForEvents($user, $events)
    {
        $this->info("Registering " . $user->first_name .' '. $user->last_name.' '. "for the following events:");

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

        $this->table($headers, $toRegister->toArray());
    }

    private function createTicket($user, $event, $pricing, $venue, $date)
    {
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
