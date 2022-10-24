<?php

namespace App\Console\Commands;
use App\Subscriptions\Models\CustomPlanFeature;
use App\Subscriptions\Models\Plan;
use Carbon\Carbon;
use Config;
use Illuminate\Console\Command;
use App\Subscriptions\Models\Subscription;
use App\Billing\InvoiceRepository;

class RenewSubscriptions extends Command
{
    private $invoiceRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew Active Subscribers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        parent::__construct();
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Renew All Renewable Subscriptions.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscriptions = Subscription::with('user', 'plan')->where('name', 'cpd')->renewable();

        $renewable = $subscriptions->Where(function($q) {
                $q->where('canceled_at', null)
                ->orwhere('canceled_at', '>=', Carbon::now()->startOfDay())
                ->where('canceled_at', '<=', Carbon::now()->endOfMonth()->endOfDay());
            })
            ->where('plan_in_group',0)
            ->where('ends_at', '<=', Carbon::now()->endOfMonth()->endOfDay())
            ->get();

        if(count($renewable) > 0) {
            $this->info("Renewing " . count($renewable) . " Subscriptions");
            $this->renew($renewable);
        } else {
            $this->warn("No Subscriptions for Renewal...");
        }
    }

    protected function renew($subscriptions)
    {
        $subscriptions->each(function($subscription) {
            if ($subscription->name != 'course'){
                if ($subscription->plan->interval == 'year'){
                    // $this->warn("Migrating to monthly CPD package..");
                    // $newPlanId = ($subscription->plan->related_plan ? $subscription->plan->related_plan : 45);

                    // /* Check if the plan is custom and if yes, we need to save the topics. */
                    // if ($subscription->plan->is_custom){
                    //     $topics = collect(CustomPlanFeature::where('user_id', $subscription->user_id)->get()->first()->features);
                    //     $subscription->changePlan($newPlanId)->save();
                    //     $this->saveComprehensiveYearlyTopics($topics, $subscription);
                    // }else{
                    //     $subscription->changePlan($newPlanId)->save();
                    // }
                    if($subscription->plan->id == 45) {
                        $subscription->changePlan(45)->updatePeriod()->save();
                    }
                    else {
                        $user = $subscription->user;
                        if($user) {
                        $user->subscriptions->where('name','cpd')->each(function ($subscription){
                            $subscription->delete();
                        });
                
                        // Change the plan for the user.
                        $plan = Plan::find(45);
                        $user->newSubscription('cpd', $plan)->create();
                        $user = $user->fresh();
                        }
                    }
                }
            }
            if ($subscription->plan->interval == 'month'){
            //    if($subscription->user->status == 'active'){
                if($subscription->user) {
                    $planDetails = $subscription->user->subscriptionUpgrade();
                   if($planDetails){
                       $details = json_encode($subscription->user->subscriptionUpgrade(),true);
                       $features = isset($details["features"])?$details["features"]:[];
                        $subscription->changePlan($planDetails->new_plan_id)->updatePeriod()->save();
                        $invoice = $this->invoiceRepository->createChildSubscriptionInvoice($subscription->user, $subscription->fresh());
                        $invoice->save();
                        $planDetails->is_completed = 1;
                        $planDetails->save();
                        //$this->saveComprehensiveYearlyTopics($topics, $subscription);
                   }else{
                        $subscription->fresh()->renew();
                   }
                }
            }
        });
    }

    public function saveComprehensiveYearlyTopics($features, $subscription)
    {
        if ($subscription->plan->is_custom && !empty($features)) {
            CustomPlanFeature::create([
                'user_id' => $subscription->user->id,
                'plan_id' => $subscription->plan->id,
                'features' => $features
            ]);
        }
    }
}
