<?php

namespace App\Console\Commands;

use App\AppEvents\EventRepository;
use Illuminate\Console\Command;
use App\AppEvents\Event;
use App\Subscriptions\Models\Subscription;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendNewEventNotification;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\AppEvents\EventNotification;
use Illuminate\Support\Facades\Log;
use App\Unsubscribe;

class NewEventNotification extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:new-event-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will send notifications to users when new events are added to their subscrpition.';
    protected $eventRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EventRepository $eventRepository)
    {
        parent::__construct();
        $this->eventRepository = $eventRepository;
    }

    protected $count=0;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        DB::listen(function ($query) {
            $this->count++;
        }); 

        // Get events with notifications scheduled
        Log::info('Cron start: NewEventNotification');
        $notifications = EventNotification::where('status', 'scheduled')
            ->where('schedule_date', Carbon::now()->format('Y-m-d'))
            ->get();
        
        $events = collect();
        if($notifications->count())  {
            $event_ids = $notifications->pluck('event_id');
            $events = Event::whereIn('id', $event_ids)
                ->with('venues', 'pricings', 'pricings.features', 'pricings.features.plans')
                ->get();

            Log::info('Notifications schedule found for: '.$event_ids[0]);
        }

        if($events->count()) {

            foreach($events as $event) {
                
                $event->notifications()->update(['status' => 'in_progress']);
                $this->info('Sending new event notifications for '.$event->name);
                Log::info('Sending new event notifications for '.$event->name.' | '.$event->id);

                // Get event features
                $features = $this->getEventFeatures($event);
                Log::info('Found event features: '.count($features));

                // Get plans from features
                $plans = $this->getFeaturePlans($features);
                Log::info('Found event plans: '.count($plans));

                // Get subscribers        
                $subscribers = $this->getSubscribers($plans);
                Log::info('Found subscribers: '.count($subscribers));

                // Send email to subscribers
                $this->sendEmails($event, $subscribers);
                Log::info('Send emails complete.');

                $event->notifications()->update([
                    'status' => 'completed',
                    'emails_sent' => $subscribers->count()
                ]);
            }

        }
        else {
            $this->info("No notifications scheduled for any event.");
        }
        Log::info('Cron end: NewEventNotification');
        
    }

    /*
    * Get features from event
    */
    public function getEventFeatures($event) {

        $features = collect();
        if (count($event->venues) > 0 && $event->venues->where('type','online')->count()) {
            foreach($event->pricings as $pricing) {
                foreach($pricing->features as $feature) {
                    $features->push($feature);
                }
            }
        }
        $features = $features->unique();

        return $features;

    }

    /*
    * Get plans from given features
    */
    public function getFeaturePlans($features) {

        $plans = collect();

        foreach($features as $feature) {
            foreach($feature->plans as $plan) {
                // Only paid plans
                if($plan->price) {
                    $plans->push($plan);
                }
            }
        }

        return $plans;

    }

    public function getPlanUpcomingEvents($plan)
    {
        $events = collect();

        $plan->features->each(function ($feature) use($events){
            if (count($feature->pricings)){
                $feature->pricings->each(function ($pricing) use ($events){
                    if ($pricing->event){
                        $events->push($pricing->event);
                    }
                });
            }
        });

        $filtered = $events->filter(function ($event) {
            if($event->end_date >= Carbon::now()) {
                if (count($event->venues) > 0 && $event->venues->where('type','online')->count()) {
                    return $event;
                }
            }
        });

        $filtered = $filtered->sortBy('end_date', 0);
        $filtered = $filtered->unique('id'); 
        return $filtered;
    }

    /*
    * Get subscribers from plans
    */
    public function getSubscribers($plans)
    {
        $users = collect();


        $subscriptions = Subscription::whereIn('plan_id', $plans->pluck('id'))
            ->with('user','user.tickets','user.subscriptions', 'user.subscriptions.plan', 'user.employing_companies', 'user.employing_companies.user', 'user.employing_companies.user.subscriptions', 'user.employing_companies.user.subscriptions.plan', 'user.company', 'user.company.staff')
            ->active()
            ->get();
        
        $subscriptions->each(function ($subscription) use ($users){
            if ($subscription->active()) {
                $user = $subscription->user;
                if($user->status == 'active'){
                    $user->user_type = 'normal';
                    $users->push($user);

                    // If user hase company and employees
                    if($user->company) {
                        if($user->company->staff->count() > 0) {
                            foreach($user->company->staff as $staff) {
                                $staff->user_type = 'staff';
                                $users->push($staff);
                            }
                        }
                    }
                }
            }
        });
        
        return $users;

    }

    /*
    * Send emails to subscribers
    */
    public function sendEmails($event, $subscribers) {
        $unSubscribers = Unsubscribe::get()->pluck('email')->toArray();
        foreach($subscribers->unique('email') as $subscriber) {
           if(!in_array($subscriber->email,$unSubscribers)){ 
            $userType = $subscriber->user_type;
            $userCompany = '';
            if($userType=='staff') {
                $userCompany = $subscriber->employing_companies[0]; 
                $currentPlan = $subscriber->employing_companies[0]->user->subscription('cpd')->plan;
            }
            else {
                $currentPlan = $subscriber->subscription('cpd')->plan;
            }
            $currentPlanEvents = $this->getPlanUpcomingEvents($currentPlan);
            $purchasedEventIds = $subscriber->tickets->pluck('event_id');
            
            $job = (new SendNewEventNotification($subscriber, $event, $currentPlan, $currentPlanEvents, $purchasedEventIds, $userType, $userCompany));
            $this->dispatch($job);

            $this->info('Email sent to: '.$subscriber->email);
            Log::info('Email sent to: '.$subscriber->email);
            }

        }
        $this->info('Total emails sent to '.$subscribers->count().' subscribers.');
        $this->info("Queries: ".$this->count);

        Log::info("Queries: ".$this->count);

    }
    
}

