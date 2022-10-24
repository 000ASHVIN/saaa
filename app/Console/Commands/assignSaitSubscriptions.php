<?php

namespace App\Console\Commands;

use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Ticket;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class assignSaitSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:saitCPD';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will register all the sait cpd subscriptions for the sait redirect events.';

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
        $this->assignSAITCpdEvents();
        $this->assignSaitCpdFreeEvents();
    }

    private function assignSAITCpdEvents()
    {
        $events = $this->getEvents('tax_event');
        $users = $this->getAllCpdSubscribers();
        $this->checkEvents($users, $events);
        $this->info("the total cpd subscribers is " .count($users));
    }

    private function assignSaitCpdFreeEvents()
    {
        $events = $this->getEvents('tax_event');
        $users = $this->getAllSaitCpdSubscribersWithNoEvent();
        $this->checkEvents($users, $events);
        $this->info("the total cpd subscribers is " .count($users));
    }

    /**
     * @param string $category
     * @return mixed
     */
    private function getEvents($category = '')
    {
        $events = Event::subscriptionEvents();

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

    private function getAllCpdSubscribers()
    {
        $users = User::where('cpd_with_sait', true)->get();
        return $users;
    }

    public function getAllSaitCpdSubscribersWithNoEvent()
    {
        $users = collect();
        $subscriptions = Subscription::with('user', 'plan', 'user')->active()->get();

        $taxSubscriptions = collect();
        $subscriptions->each(function ($subscription) use($taxSubscriptions){
           if ($subscription->plan->id == 2 ||
               $subscription->plan->id == 7 ||
               $subscription->plan->id == 3 ||
               $subscription->plan->id == 8 ||
               $subscription->plan->id == 9 ||
               $subscription->plan->id == 10){
               if ($subscription->user->status != 'suspended'){
                    $taxSubscriptions->push($subscription);
               }
           };
        });

       $taxSubscriptions->each(function ($subscription) use($users){
            $users->push($subscription->user);
       });

        return $users;
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
        $this->info("Registering " . $user->first_name .' '. $user->last_name . ' (' . $user->subscriptions()->first()->plan->name . ') ' . "for the following events:");

        $headers = ['ID', 'Event'];
        $toRegister = collect([]);

        foreach ($events->where('is_redirect', 1) as $event) {

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
