<?php

namespace App\Console\Commands;

use App\Note;
use App\Company;
use App\Users\User;
use Mockery\Exception;
use App\Billing\Invoice;
use Illuminate\Console\Command;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\SubscriptionGroup;
use App\Repositories\Subscription\upgradeSubscriptionRepository;

class AllocatePracticePlanToUser extends Command
{
    private $upgradeSubscriptionRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allocate:practice:plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will allocate Practice Plan for TTF 3006 user';

    /**
     * Create a new command instance.
     *
     */
    public function __construct(upgradeSubscriptionRepository $upgradeSubscriptionRepository)
    {
        parent::__construct();
        $this->upgradeSubscriptionRepository = $upgradeSubscriptionRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $company = Company::find(0);
       $staffs = $company->staff->unique();
       foreach($staffs as $staff)
       {
           $this->allocatePlan($company,$staff);
       }
     
    }
    public function allocatePlan($company,$user)
    {
            $admin = $company->admin();
            // $this->upgradeSubscriptionRepository->submitRequest($request, $oldPlan, $newPlan);
            //dd($this->upgradeSubscriptionRepository);
            // $company = Company::find($company);
            $subscription_group = SubscriptionGroup::where('admin_id',$admin->id)->count();
            $total = $admin->isPracticePlan();
            if ($company->admin()->subscribed('cpd') && $company->admin()->PracticePlan() && $subscription_group <= $total  && $total>0) {
                $plan = $company->admin()->subscription('cpd')->plan;
                if ($user->subscribed('cpd')) {
                    if ($user->subscription('cpd')->plan->price > 0) {
                        $oldPlan = $user->subscription('cpd')->plan;
                        $newPlan = $plan;
                        $data = ['email'=>$user->email,
                    'reason'=>'Upgrade due to Practice Plan invitation',
                    'features'=>[],
                    'payment_method'=>'','is_practice_plan'=>1];
    
                        $request = new \Illuminate\Http\Request($data);
                        $this->upgradeSubscriptionRepository->submitRequest($request, $oldPlan, $newPlan);
                    // $user->newSubscription('cpd', $plan)->create();
                    } elseif ($user->subscription('cpd')->plan->price == 0) {
                        $user->subscriptions->where('name', 'cpd')->each(function ($subscription) {
                            $subscription->delete();
                        });
            
                        $user->newSubscription('cpd', $plan)->create();
                        $this->assignCompany($user,$company,$plan);
                    }
                } else {
                    $user->newSubscription('cpd', $plan)->create();
                    $this->assignCompany($user,$company,$plan);

                }
               
            } else {
                if (!$user->subscribed('cpd')) {
                    $plan = Plan::where('price', '0')->where('interval', 'year')->get()->first();
                    $user->newSubscription('cpd', $plan)->create();
                }
            }
            
            $admin = $company->admin();
            if ($admin->additional_users > 0) {
              //  $admin->additional_users = $admin->additional_users - 1;
                //$admin->save();
            }
        
    }
    public function assignCompany($user,$company,$plan){
        $subscriptionExist = SubscriptionGroup::where('user_id',$user->id)->where('admin_id',$company->admin()->id)->first();
	if(!$subscriptionExist){
        $subscription_id = $user->subscription('cpd')->id;
        $subscriptionGroup = SubscriptionGroup::create([
        'user_id'=> $user->id,
        'admin_id'=> $company->admin()->id,
        'plan_id'=> $plan->id,
        'status'=>'1',
        'pricing_group_id'=> $company->admin()->getPricingGroup(),
        'subscription_id'=> @$subscription_id
    ]);
        }
    }
}
