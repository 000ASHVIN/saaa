<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AppEvents\EventRepository;
use App\AppEvents\Event;
use App\Subscriptions\Models\Subscription;
use DB;
use Carbon\Carbon;
use App\Jobs\SendEventBookingReminder;
use Illuminate\Foundation\Bus\DispatchesJobs;

class EventBookingReminders extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:booking-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will send reminders to users to book an upcoming event.';
    private $eventRepository;

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get the upcoming Webinars
        $events = $this->eventRepository->upcomingEventForBookingReminder();

        if($events->count()) {
            $this->info('We have ' . count($events).' upcoming events.');

            foreach($events as $event) {

                $this->info('Sending event reminders for '.$event->name);

                // Get event features
                $features = $this->getEventFeatures($event);

                // Get plans from features
                $plans = $this->getFeaturePlans($features);

                // Get subscribers        
                $subscribers = $this->getSubscribers($plans);

                // Send email to subscribers
                $this->sendEmails($event, $subscribers);

            }

        } else {
            $this->info('There is no event today or tomorrow');
        }
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
                $plans->push($plan);
            }
        }

        return $plans;

    }

    /*
    * Get subscribers from plans
    */
    public function getSubscribers($plans)
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIn('plan_id', $plans->pluck('id'))->with('user','user.tickets','user.subscriptions', 'user.subscriptions.plan', 'user.employing_companies', 'user.employing_companies.user', 'user.employing_companies.user.subscriptions', 'user.employing_companies.user.subscriptions.plan')->active()->get();
        
        $subscriptions->each(function ($subscription) use ($users){
            if ($subscription->active()) {
                $user = $subscription->user;

                if($user->settings && key_exists('event_notifications', $user->settings)) {

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
            }
        });
        
        return $users;

    }

    /*
    * Send emails to subscribers
    */
    public function sendEmails($event, $subscribers) {

        foreach($subscribers->unique('email') as $subscriber) {

            // Get registered events
            $registered = collect([]);
            foreach ($subscriber->tickets as $ticket) {
                $registered->push($ticket->event_id);
            }

            if(!in_array($event->id, $registered->toArray())) {

                $job = (new SendEventBookingReminder($subscriber, $event));
                $this->dispatch($job);

                $this->info('Sent email to '.$subscriber->email);
            }
        }

    }
}
