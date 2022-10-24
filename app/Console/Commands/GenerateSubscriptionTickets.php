<?php

namespace App\Console\Commands;

use Config;
use Carbon\Carbon;
use App\Users\User;
use App\AppEvents\Ticket;
use App\AppEvents\Pricing;
use Illuminate\Console\Command;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Feature;
use App\Subscriptions\Models\Subscription;

class GenerateSubscriptionTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:subscriber-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will assign all the Subscription events to the subscribes';

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
        
        /*
         * First, we need all the plans
         */
        $plans = Plan::has('subscriptions')->with('features','features.pricings','features.pricings.event', 'features.pricings.event.venues', 'features.pricings.event.venues.dates', 'features.pricings.event.pricings', 'features.pricings.event.pricings.features')->where('id', '!=', 45)->get();

        /* Loop through the plans and some magig */
        foreach ($plans as $plan){
            $this->info('We are working with '.$plan->name);

            /* Get all the events for this plan*/
            $events = $this->getPlanEvents($plan);

            /* Get all the subscribers for this plan */
            $subscribers = $this->getSubscribers($plan);

            /* Check events for the subscribers */
            $this->checkEvents($subscribers, $events);
        }
        
    }

    public function getPlanEvents($plan)
    {
        $events = collect();

        $plan->features->each(function ($feature) use($events){
            if ($feature->pricings->count()){
                $feature->pricings->each(function ($pricing) use ($events){
                    if ($pricing->event){
                        $events->push($pricing->event);
                    }
                });
            }
        });

        $filtered = $events->filter(function ($event) {
            // if($event->is_active){
            if ($event->venues->count() > 0 && $event->venues->where('type','online')->count()) {
                return $event;
            } elseif ($event->end_date <= Carbon::now()) {
                return $event;
            }
            // }
        });

        return $filtered;
    }

    public function getSubscribers($plan)
    {
        $users = collect([]);

        $subscriptions = User::select('users.*')->with('tickets')->join('subscriptions', function($join) use($plan)
            {
            $join->on('subscriptions.user_id', '=', 'users.id');
            $join->where('subscriptions.name', '=', 'cpd');
            $join->where('subscriptions.plan_id', '=',$plan->id);

            })->where('subscriptions.name','cpd')->where('subscriptions.ends_at','>=',Carbon::now())
            ->where('subscriptions.suspended',0)
            ->where('subscriptions.deleted_at', null)
            ->groupBy('subscriptions.user_id')->get();
            $users = collect($subscriptions);
        return $users;
    }

    private function checkEvents($subscribers, $events)
    {
        $finalEvent = $events;
        foreach ($subscribers as $subscriber) {
            $events = $finalEvent;
            if($subscriber->tickets->count()) {
                // $registered = collect([]);
                $registered = collect($subscriber->tickets->pluck('event_id'));
                if($subscriber->employing_companies->count()){
                   $companyEvent = $this->getCompanyEvents($subscriber);
                   $events = $events->merge($companyEvent);
                }
                // foreach ($subscriber->tickets as $ticket) {
                //     if($events->where('id', $ticket->event_id))
                //         $registered->push($ticket->event_id);
                // }

                $filtered = $events->reject(function($event) use ($registered) {
                    return in_array($event->id, $registered->toArray());
                })->unique('id');

                if(count($filtered)) {
                    $this->registerUserForEvents($subscriber, $filtered);
                }
            } else {
                $this->registerUserForEvents($subscriber, $events->unique('id'));
            }
            continue;
        }
    }

    private function getCompanyEvents($user)
    { 
        $events = collect([]);
        if($user->employing_companies->count()){
            $plan =  $user->employing_companies->first()->user->subscription('cpd')->plan()->with('features','features.pricings','features.pricings.event', 'features.pricings.event.venues', 'features.pricings.event.venues.dates', 'features.pricings.event.pricings', 'features.pricings.event.pricings.features')->first();
            $events = $this->getPlanEvents($plan);

        }
        return $events;
        
    }
    private function registerUserForEvents($user, $events)
    {
        if (count($events)){
            $headers = ['ID', 'Event'];
            $toRegister = collect([]);

            foreach ($events as $event) {

                if ($user->employing_companies->count()){
                    $subscriptionPlan = $user->employing_companies[0]->user->subscription('cpd')->plan;
                    $startOfSubscriptionMonth = $user->employing_companies[0]->user->subscription('cpd')->created_at->startOfMonth()->startOfDay();
                }else{
                    $subscriptionPlan = $user->subscription('cpd')->plan;
                    $startOfSubscriptionMonth = $user->subscription('cpd')->created_at->startOfMonth()->startOfDay();
                }

                $venue = null;
                foreach($event->venues as $v) {
                    if($v->type=='online' && $venue == null) {
                        $venue = $v;
                    }
                }
                
                $date = ( $venue->dates)? $venue->dates->first():null;
                $pricing = null;
                foreach($event->pricings as $p) {
                    if($p->venue_id == $venue->id && $pricing == null  && $p->features->count()) {
                        $pricing = $p;
                    }
                }

                if ($subscriptionPlan->last_minute == false && $pricing->features && ! $pricing->features->contains('slug', 'compliance-and-legislation-update')){
                    if($event->end_date->lt($startOfSubscriptionMonth)) {
                        continue;
                    }
                }

                if(! $venue || ! $date || ! $pricing){
                    continue;

                } else {
                    $toRegister->push([
                        'id' => $event->id,
                        'name' => $event->name
                    ]);

                    // Condition for past events only
                    $event_date = Carbon::parse($date->date);
                    if($event_date->lt(Carbon::today())) {
                        $this->createTicket($user, $event, $pricing, $venue, $date);
                    }
                }
            }
        }
    }

    private function createTicket($user, $event, $pricing, $venue, $date)
    {
        $can_continue = false;

        foreach ($pricing->features as $feature) {
            if ($user->employing_companies->count()){
                if ($user->employing_companies[0]->user->subscription('cpd')->ability()->canUse($feature->slug)){
                    $can_continue = true;
                }
            }elseif($user->subscription('cpd')->ability()->canUse($feature->slug)){
                $can_continue = true;
            }
        }

        if(! $can_continue)
            return;

        $this->warn("Registering " . $user->first_name .' '. $user->last_name .' '.$user->subscription('cpd')->plan->name .' '. "for {$event->name}");

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
