<?php

namespace App\Console\Commands;

use App\PreCPD;
use Carbon\Carbon;
use App\Users\User;
use App\Mailers\UserMailer;
use App\Jobs\CreateUserAccount;
use Illuminate\Console\Command;
use App\Billing\InvoiceRepository;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Period;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Bus\Dispatcher;
use App\Subscriptions\Models\Subscription;
use App\Subscriptions\Models\CustomPlanFeature;

class SwitchSubscriptions extends Command
{
    private $invoiceRepository;
    private $userMailer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'switch:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Switch from Old System Subscriptions to new Subscriptions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InvoiceRepository $invoiceRepository, UserMailer $userMailer)
    {
        parent::__construct();
        $this->invoiceRepository = $invoiceRepository;
        $this->userMailer = $userMailer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscriptions = Subscription::get()->groupBy('user_id'); 

        foreach($subscriptions as $subscription) {
            if($subscription->count() > 1) {
                $this->info($subscription->first()->user->id);
            }
        }

        // Step 1: Replace tables
        // $this->info("Replacing Subscription Tables...");
        // Artisan::call('migrate --force');

        // Step 2: Setup New Plans
        // $this->info("Setting up new Plans...");
        // Artisan::call('db:seed --force');
        
        // Step 3: Assign old Subscribers to new Plans
        // $this->info("Loading old subscribers back on new Plans...");
        // $this->assignOldSubscribers();

        // Step 4: Change Plan for Users That wants to renew, and set start date 1 January 2017
        // $this->info("Processing Subscription Renewals...");
        // $this->processRenewals();

        // Step 4: Process New Subscriber Signups starting 1 December 2016        
        // $this->info("Processing New Subscriptions...");
        // $this->processNewSubscriptions();

    }

    protected function assignOldSubscribers()
    {
        $results = Excel::load(public_path('subs.xlsx'), function($reader) {
        })->get();

        foreach ($results as $result) {

            $user = User::find($result->user_id);

            if(! $user->subscribed('cpd')) {
                $plan = Plan::find($result->planid);
                $user->newSubscription('cpd', $plan)->create();
            }

            $user->fresh()->subscription('cpd')->cancelAt(Carbon::parse('31 December 2016'));
        }
    }

    protected function processRenewals()
    {
        $renewals = \DB::table('renewals')->get();

        foreach ($renewals as $renewal) {

            $user = User::find($renewal->user_id);

            if($user->subscribed('cpd'))
            {
                $current_subscription = $user->subscription('cpd')->plan->id;
                $new_subscription_id = 3;

                if($current_subscription == 4)
                    $new_subscription_id = 1;

                $user->subscription('cpd')->changePlan($new_subscription_id)->save();

                $period = new Period('month', 1, Carbon::parse('1 December 2016'));

                $user->subscription('cpd')->starts_at = $period->getStartDate();
                $user->subscription('cpd')->ends_at = $period->getEndDate();
                $user->subscription('cpd')->canceled_at = NULL;

                $user->subscription('cpd')->save();
            }
        }
    }

    protected function processNewSubscriptions()
    {
        $precpds = PreCPD::all();

        // Foreach User, we need to create an account, 
        // If the user already has an account we need to subscribe them to the required subscription
        // If user already has a subscription we need to change their Subscription Plan!
        foreach ($precpds as $cpd) {

            $plans = [
                'option-four-build-your-own-package' => 9,
                'option-comprehensive-accountancy-practice' => 9
            ];

            $base_features = [
                'monthly-accounting-webinar-various-topics',
                'monthly-sars-and-tax-update-webinar',
                'monthly-legislation-update-webinar',
                'monthly-practice-management-webinar'
            ];

            if(! User::withTrashed()->where('email', strtolower($cpd->email))->exists()) {

                if(isset($plans[$cpd->plan])) {

                    if(! empty($cpd->events)) {
                        $pass = str_random(10);
                    
                        // Create new Account
                        $new_user = app(Dispatcher::class)->dispatchFromArray(CreateUserAccount::class, [
                            'first_name' => $cpd->first_name,
                            'last_name' => $cpd->last_name,
                            'email' => $cpd->email,
                            'password' => $pass
                        ]);

                        $this->userMailer->sendNewMembershipWithPasswordTo($new_user, $pass);

                        if($cpd->payment_option == 'debit_order')
                            $this->userMailer->sendDebitOrderMailTo($new_user);

                            // Create new Subscription
                            $plan = Plan::find($plans[$cpd->plan]);
                            $new_user->fresh()->newSubscription('cpd', $plan)->create();

                            // Generate Custom Plan Features
                            CustomPlanFeature::create([
                                'user_id' => $new_user->id,
                                'plan_id' => $plan->id,
                                'features' => array_merge($cpd->events, $base_features)
                            ]);

                            // Generate Subscription Invoice
                            $invoice = $this->invoiceRepository->createSubscriptionInvoice($new_user, $plan);
                            $invoice->save();

                            $cpd->delete();
                    }
                }
            } else {
                $user = User::withTrashed()->where('email', strtolower($cpd->email))->first();

                if($user->trashed())
                    $user->restore();

                if(isset($plans[$cpd->plan])) {

                    if(! empty($cpd->events)) {
                        // Create new Subscription
                        $plan = Plan::find($plans[$cpd->plan]);

                        if(! $user->subscribed('cpd')) {
                            $user->fresh()->newSubscription('cpd', $plan)->create();

                            // Generate Subscription Invoice
                            $invoice = $this->invoiceRepository->createSubscriptionInvoice($user, $plan);
                            $invoice->save();

                        } else {
                            $user->subscription('cpd')->changePlan($plan)->save();
                        }

                        // Generate Custom Plan Features
                        // CustomPlanFeature::create([
                        //     'user_id' => $user->id,
                        //     'plan_id' => $plan->id,
                        //     'features' => array_merge($cpd->events, $base_features)
                        // ]);

                        $period = new Period('month', 1, Carbon::parse('1 December 2016'));

                        $user->fresh()->subscription('cpd')->starts_at = $period->getStartDate();
                        $user->fresh()->subscription('cpd')->ends_at = $period->getEndDate();
                        $user->fresh()->subscription('cpd')->canceled_at = null;
                        $user->fresh()->subscription('cpd')->save();

                        $cpd->delete();
                    }
                }
            }
        }
    }
}
