<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Note;
use App\Users\User;
use App\Subscriptions\Models\Plan;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Subscriptions\Models\CustomPlanFeature;
use Illuminate\Support\Facades\Mail;

class SubscribeUserToPlan extends Job implements SelfHandling
{
    public $user_id;

    public $plan;

    public $features;

    public $payment_option;

    /**
     * Create a new job instance.
     *
     * @param $user_id
     * @param $plan
     * @param $features
     * @param $payment
     */
    public function __construct($user_id, $plan, $features, $payment)
    {
        $this->user_id = $user_id;
        $this->plan = $plan;
        $this->features = $features;
        $this->payment_option = $payment;
    }

    /**
     * Execute the job.
     *
     * @return static
     */
    public function handle()
    {
        $user = User::find($this->user_id);
        $plan = Plan::find($this->plan);

        if ($this->payment_option == 'cc'){
            $method = 'credit_card';

        }elseif ($this->payment_option == 'debit'){
            $method = 'debit_order';

        }else{
            $method = 'eft';
        }

        $subscription = $user->newSubscription('cpd', $plan)->create();
        $subscription->update(['payment_method' => $method]);
        $subscription->save();

        if($plan->is_custom && ! empty($this->features)) {
            CustomPlanFeature::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'features' => $this->features,
            ]);
        }

        $note = new Note([
            'type' => 'new_subscription',
            'description' => 'New CPD Subscription started for '.$plan->name.' (' . ucfirst($plan->interval) . 'ly)',
            'logged_by' => 'System',
        ]);
        $user->notes()->save($note);

        if(env('APP_THEME') == 'taxfaculty') {

            // Send mailer for specific plans
            $plans_data = [
                // Tax Accountant Package
                '59' => ['name' => 'Tax Accountant CPD Subscription Package', 'url' => 'profession/tax-and-accounting'],
                '57' => ['name' => 'Tax Accountant CPD Subscription Package', 'url' => 'profession/tax-and-accounting'],

                // BUILD YOUR OWN CPD PACKAGE
                '60' => ['name' => 'Build your own CPD Subscription Package', 'url' => 'profession/custom-practitioner'],
                '62' => ['name' => 'Build your own CPD Subscription Package', 'url' => 'profession/custom-practitioner'],

                // Tax Practitioner Cpd Package
                '61' => ['name' => 'Tax Practitioner CPD Subscription Package', 'url' => 'profession/general-tax-practitioner'],
                '55' => ['name' => 'Tax Practitioner CPD Subscription Package', 'url' => 'profession/general-tax-practitioner'],

                // Tax Technician Package
                '58' => ['name' => 'Tax Technician CPD Subscription Package', 'url' => 'profession/tax-technician'],
                '54' => ['name' => 'Tax Technician CPD Subscription Package', 'url' => 'profession/tax-technician'],
            ];

            if(isset($plans_data[$plan->id])) {
                $plan_data = $plans_data[$plan->id];
                Mail::send('emails.subscription.plan_mailer', ['plan_data' => $plan_data, 'user' => $user], function ($m) use($user, $plan_data){
                    $m->from(config('app.email'), config('app.name'));
                    $m->to($user->email)->subject('Welcome to your '.$plan_data['name']);
                });

            }

        }


        return $subscription;
    }
}
