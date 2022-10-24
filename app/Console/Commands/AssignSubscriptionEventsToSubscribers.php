<?php

namespace App\Console\Commands;
error_reporting(0);
use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Ticket;
use App\Company;
use App\CompanyUser;
use App\Jobs\AssignTicketToUserJob;
use App\Subscriptions\Models\CustomPlanFeature;
use App\Subscriptions\Models\Feature;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AssignSubscriptionEventsToSubscribers extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribers:assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign Subscription Events to Subscribers';

    public function __construct()
    {
        parent::__construct();
    }

    // This is the methods that will be used and assign events to relevant users
    public function handle()
    {
        $this->info('Starting....');
        $this->getIndependantReviewerAndAssign();
        $this->getJuniorBookkeeperAndAssign();
        $this->getAccountingOfficerAndAssign();
        $this->getComprehensiveAndAssign();
        $this->getAccountingOnlyAndAssign();
        $this->getTaxAccountantAndAssign();
        $this->getICBAccountingOfficerAndAssign();
        $this->getICBAccountingOnlyAndAssign();
        $this->getICBbookeperAndAssign();
//
//        // New Plans 2018
        $this->getMonthlyLegislationUpdateAndAssign();
        $this->getCharteredAccountantAndAssign();
        $this->getProfessionalAccountantAndAssign();
        $this->getBusinessAccountantsAndAssign();
        $this->getCertifiedBookkeepersAndAssign();
        $this->getCompanySecretarysAndAssign();
        $this->getTaxPractitionerAndAssign();
        $this->getTaxBuildYourOwnAndAssign();
        $this->getFreeAndAssign();
        $this->getLastMinuteAndAssign();

//      This will assign company events to company staff members
        $this->getCompanyUsersAndAssign();
        $this->info('Done!');
    }


    /*
     * Monthly Legislation Update
     */
    public function getMonthlyLegislationUpdateAndAssign()
    {
        $plans = Plan::whereIn('id', ['23'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Monthly Legislation Update subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getMonthlyLegislationUpdateSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Monthly Legislation Update subscribers');
    }

    public function getMonthlyLegislationUpdateSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['23', '24']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });
        return $users;
    }

    /*
     * Chartered Accountant
     */
    public function getCharteredAccountantAndAssign()
    {
        $plans = Plan::whereIn('id', ['25'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Chartered Accountant subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getCharteredAccountantSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Chartered Accountant subscribers');
    }

    public function getCharteredAccountantSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['25', '26', '39']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    /*
     * Professional Accountant
     */
    public function getProfessionalAccountantAndAssign()
    {
        $plans = Plan::whereIn('id', ['27'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Professional Accountant subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getProfessionalAccountantSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Professional Accountant subscribers');
    }

    public function getProfessionalAccountantSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['27', '28', '41']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    /*
     * Business Accountant in Practice
     */
    public function getBusinessAccountantsAndAssign()
    {
        $plans = Plan::whereIn('id', ['29'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Business Accountant in Practice subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getBusinessAccountantsSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Business Accountant in Practice subscribers');
    }

    public function getBusinessAccountantsSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['29', '30', '37']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    /*
     * Certified Bookkeeper
     */
    public function getCertifiedBookkeepersAndAssign()
    {
        $plans = Plan::whereIn('id', ['31'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Certified Bookkeeper subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getCertifiedBookkeepersSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Certified Bookkeeper subscribers');
    }

    public function getCertifiedBookkeepersSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['31', '32', '38']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    /*
     * Company Secretary
     */
    public function getCompanySecretarysAndAssign()
    {
        $plans = Plan::whereIn('id', ['33'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Company Secretary subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getCompanySecretarysSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Company Secretary subscribers');
    }

    public function getCompanySecretarysSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['33', '34', '40']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    /*
     * Tax Practitioner
     */
    public function getTaxPractitionerAndAssign()
    {
        $plans = Plan::whereIn('id', ['35'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Tax Practitioner subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getTaxPractitionerSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Tax Practitioner subscribers');
    }

    public function getTaxPractitionerSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['35', '36', '42']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    public function getTaxBuildYourOwnAndAssign()
    {
        $plans = Plan::whereIn('id', ['43'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Build your own subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getTaxBuildYourOwnSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Build your own subscribers');
    }

    public function getTaxBuildYourOwnSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['43', '44']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }


    public function getFreeAndAssign()
    {
        $plans = Plan::whereIn('id', ['45'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Free subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getFreeSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Free subscribers');
    }

    public function getFreeSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['45']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    /*
     * Assign last Minute CPD Events
     */

    public function getLastMinuteAndAssign()
    {
        $plans = Plan::whereIn('id', ['46'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Last Minute subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getLastMinuteSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Last Minute subscribers');
    }

    public function getLastMinuteSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['46']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }


    /*
     * Ending with 2018 Subscriptions.
     */

    /*
     * Independant Reveiwer Package
     */
    public function getIndependantReviewerAndAssign()
    {
        $plans = Plan::whereIn('id', ['1'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Independant Reviewer subscribers');
        $events = $this->getPlanEvents($plans);

        $users = $this->getIndapendantReviewerSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Independant Reviewer subscribers');
    }

    public function getIndapendantReviewerSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['1', '6']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    /*
     * Junior Bookkeeper & Accountant
     */
    public function getJuniorBookkeeperAndAssign()
    {
        $plans = Plan::whereIn('id', ['2'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Junior Bookeeper subscribers');

        $events = $this->getPlanEvents($plans);
        $users = $this->getJuniorBookkeeperSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Junior Bookeeper subscribers');
    }

    public function getJuniorBookkeeperSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['2', '7']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }


    /*
     * Accounting Officer
     */
    public function getAccountingOfficerAndAssign()
    {
        $plans = Plan::whereIn('id', ['3'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Accounting Officer subscribers');

        $events = $this->getPlanEvents($plans);
        $users = $this->getAccountingOfficerSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Accounting Officer subscribers');
    }

    /*
     * Tax Accountant
     */
    public function getTaxAccountantAndAssign()
    {
        $plans = Plan::whereIn('id', ['21'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Tax Accountant subscribers');

        $events = $this->getPlanEvents($plans);
        $users = $this->getTaxAccountantSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Tax Accountant subscribers');
    }

    public function getICBbookeperAndAssign()
    {
        $plans = Plan::whereIn('id', ['18'])->with('features', 'features.pricings')->get();

        $this->info('Starting with ICBA Bookkeeper/Junior Accountant subscribers');

        $events = $this->getPlanEvents($plans);
        $users = $this->getICBbookeeperSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with ICBA Bookkeeper/Junior Accountant subscribers');
    }

    public function getICBAccountingOfficerAndAssign()
    {
        $plans = Plan::whereIn('id', ['19'])->with('features', 'features.pricings')->get();

        $this->info('Starting with ICBA Accounting Officer subscribers');

        $events = $this->getPlanEvents($plans);
        $users = $this->getICBAccountingOfficerSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with ICBA Accounting Officer subscribers');
    }

    public function getICBAccountingOnlyAndAssign()
    {
        $plans = Plan::whereIn('id', ['20'])->with('features', 'features.pricings')->get();

        $this->info('Starting with ICBA Accounting Only Package subscribers');

        $events = $this->getPlanEvents($plans);
        $users = $this->getICBAccountingOnlySubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with ICBA Accounting Only Package subscribers');
    }

    public function getAccountingOfficerSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['3', '8']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    public function getTaxAccountantSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['21', '22']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    public function getICBbookeeperSubscribers()
    {
        $users = collect([]);

        $monthly = Subscription::where('plan_id', '18')->with('user')->Active()->get();
        $monthly->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    public function getICBAccountingOfficerSubscribers()
    {
        $users = collect([]);

        $monthly = Subscription::where('plan_id', '19')->with('user')->Active()->get();
        $monthly->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });
        return $users;
    }

    public function getICBAccountingOnlySubscribers()
    {
        $users = collect([]);

        $monthly = Subscription::where('plan_id', '20')->with('user')->Active()->get();
        $monthly->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });
        return $users;
    }


    /*
     * Comprehensive
     */
    public function getComprehensiveAndAssign()
    {
        $plans = Plan::whereIn('id', ['9'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Comprehensive subscribers');

        $events = $this->getPlanEvents($plans);
        $users = $this->getComprehensiveSubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Comprehensive subscribers');
    }

    public function getComprehensiveSubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['9', '10']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    /*
     * Accounting Only
     */
    public function getAccountingOnlyAndAssign()
    {
        $plans = Plan::whereIn('id', ['11'])->with('features', 'features.pricings')->get();

        $this->info('Starting with Accounting Only subscribers');

        $events = $this->getPlanEvents($plans);
        $users = $this->getAccountingOnlySubscribers();
        $this->checkEvents($users, $events);

        $this->info('Ending with Accounting Only subscribers');
    }

    public function getAccountingOnlySubscribers()
    {
        $users = collect([]);

        $subscriptions = Subscription::whereIN('plan_id', (['11', '12']))->active()->get();
        $subscriptions->each(function ($subscription) use ($users){
            $users->push($subscription->user);
        });

        return $users;
    }

    /**
     * @param $plans
     * @return mixed
     * @internal param $events
     */
    public function getPlanEvents($plans)
    {
        $events = collect();
        $plans->each(function ($plan) use($events){
            if ($plan->is_custom){
                $customs = collect();
                    $customs->push(Feature::where('slug', 'compliance-and-legislation-update')->first());

                    foreach ($customs as $custom){
                        foreach ($custom->pricings as $pricing){
                            if ($pricing->event){
                                $events->push($pricing->event);
                            }
                        }
                    }

                    $plan->features->each(function ($feature) use($events){
                        if (count($feature->pricings)){
                            $feature->pricings->each(function ($pricing) use ($events){
                                if ($pricing->event){
                                    $events->push($pricing->event);
                                }
                            });
                        }
                    });

            }else{
                $plan->features->each(function ($feature) use($events){
                    if (count($feature->pricings)){
                        $feature->pricings->each(function ($pricing) use ($events){
                            if ($pricing->event){
                                $events->push($pricing->event);
                            }
                        });
                    }
                });
            }
        });


        $filtered = $events->filter(function ($event) {
            if (count($event->venues) == 1 && $event->venues->first()->type == 'online') {
                return $event;
            } elseif ($event->end_date <= Carbon::now()) {
                return $event;
            }
        });
        return $filtered;
    }

    // This will check if the user already has the relevant event
    private function checkEvents($users, $events)
    {
        foreach ($users as $user) {
            if(count($user->tickets)) {
                $registered = collect([]);

                foreach ($user->tickets as $ticket) {
                    if($events->where('id', $ticket->event_id))
                        $registered->push($ticket->event_id);
                }

                $filtered = $events->reject(function($item) use ($registered) {
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

                if ($user->hasCompany()->company){
                    $subscriptionPlan = $user->hasCompany()->company->admin()->subscription('cpd')->plan;
                    $startOfSubscriptionMonth = $user->hasCompany()->company->admin()->subscription('cpd')->created_at->startOfMonth()->startOfDay();
                }else{
                    $subscriptionPlan = $user->subscription('cpd')->plan;
                    $startOfSubscriptionMonth = $user->subscription('cpd')->created_at->startOfMonth()->startOfDay();
                }

                $venue = $event->venues()->where('type', 'online')->first();
                $date = $venue->dates->first();
                $pricing = Pricing::where('event_id', $event->id)->where('venue_id', $venue->id)->first();

                if ($subscriptionPlan->last_minute == false && ! $pricing->features->contains('slug', 'compliance-and-legislation-update')){
                    if($event->end_date->lt($startOfSubscriptionMonth)) {
                        continue;
//                          $dateFormatted = $startOfSubscriptionMonth->format('d/m/Y');
//                          $this->warn("{$event->name} was on {$date->date} and you ({$user->first_name}) subscribed on {$dateFormatted}");
                    }
                }

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
        $can_continue = false;

        foreach ($pricing->features as $feature) {
            if ($user->hasCompany()){
               if ($user->hasCompany()->company->admin()->subscription('cpd')->ability()->canUse($feature->slug)){
                   $can_continue = true;
               }
            }elseif($user->subscription('cpd')->ability()->canUse($feature->slug)){
                $can_continue = true;
            }
        }

        if(! $can_continue)
            return;
//
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

    public function getCompanyUsersAndAssign()
    {
        $this->info('Starting with Company Subscription subscribers');
        $companies = Company::all();

        foreach ($companies as $company) {
            $users = collect();
            foreach ($company->staff as $member){
                $users->push($member);
            }
            $this->CompanyUserPlanEvents($users);
        }
        $this->info('Ending with Company Subscription subscribers');
    }

    public function CompanyUserPlanEvents($users)
    {
        foreach ($users as $user){
            $plans = collect();
            $user = CompanyUser::where('user_id', $user->id)->first();
            $plans->push($user->company->admin()->subscription('cpd')->plan);

            $events = $this->getPlanEvents($plans);
            $this->checkEvents($user->company->staff, $events);
        }
    }
}
