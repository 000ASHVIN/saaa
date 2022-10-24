<?php

namespace App\Repositories\Subscription;

use App\Billing\InvoiceRepository;
use App\Jobs\SendEventTicketInvoiceJob;
use App\Jobs\sendUpgradeRequest;
use App\Note;
use App\Repositories\CreditMemo\CreditMemoRepository;
use App\Subscriptions\Models\CustomPlanFeature;
use App\Subscriptions\Models\Feature;
use App\Subscriptions\Models\Period;
use App\Subscriptions\Models\Plan;
use App\UpgradeFeatures;
use App\UpgradeSubscription;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;
use App\Subscriptions\Models\SubscriptionGroup;

class upgradeSubscriptionRepository
{
    use DispatchesJobs;
    private $upgradeSubscription, $invoiceRepository;
    private $creditMemoRepository;

    public function __construct(UpgradeSubscription $upgradeSubscription, InvoiceRepository $invoiceRepository,CreditMemoRepository $creditMemoRepository)
    {
        $this->upgradeSubscription = $upgradeSubscription;
        $this->invoiceRepository = $invoiceRepository;
        $this->creditMemoRepository = $creditMemoRepository;
    }

    public function submitRequest($request, $oldPlan, $newPlan)
    {
        $member = User::where('email', $request->email)->first();
        $user = auth()->user();
        $reason = $request->reason;
        $features = $request->features;
        $payment_method = $request->payment_method;

        if ($request->bf_discount == 1){
            $bf = true;
        }else{
            $bf = false;
        }
        $is_practice_plan = 0;
        if($request->is_practice_plan)
        {
            $is_practice_plan = 1;
        }

        try{
            $upgrade = $this->createNewUpgrade($user, $member, $newPlan, $oldPlan, $reason, $payment_method, $bf,$is_practice_plan);
            $this->setFeatures($features, $upgrade);
            $this->dispatch(new sendUpgradeRequest($member));
            return $upgrade;

        }catch (Exception $exception){
            return $exception;
        }
    }

    public function doTheUpgrade($upgrade)
    {
        $user = User::find($upgrade->user_id);
        $member = User::find($upgrade->member_id);

        // New Subscription Plan.
        $plan = Plan::find($upgrade->new_subscription_package);
        $oldPlan = Plan::find($upgrade->old_subscription_package);

       DB::transaction(function () use($user, $member, $plan, $oldPlan, $upgrade) {

           // Check for Black Friday Discount
           if ($upgrade->bf){
               $plan->price = $plan->bf_price;
           }

           if ($member->subscription('cpd')->plan->is_custom){
                $member->custom_features->delete();
           }

           // Change the plans for the user.
           $member->subscription('cpd')->plan_id = $plan->id;
           $member->subscription('cpd')->save();
           if($upgrade->is_practice_plan)
           {
               if($member->employing_companies && $member->employing_companies->first()->admin())
               {
                   $admin = $member->employing_companies->first()->admin();
                $subscriptionGroup = SubscriptionGroup::create([
                    'user_id'=> $member->id,
                    'admin_id'=> $admin->id,
                    'plan_id'=> $plan->id,
                    'status'=>'1',
                    'pricing_group_id'=> $admin->getPricingGroup(),
                    'subscription_id'=> @$admin->subscription('cpd')->id
                ]);
               }
               if($admin->additional_users > 0)
               {
                 //  $admin->additional_users = $admin->additional_users - 1;
                  // $admin->save();
               }
            
           }

           $this->saveComprehensiveTopics($upgrade->features, $plan, $member);

            if ($plan->price > 0 && !$upgrade->is_practice_plan){
                $invoice = $this->invoiceRepository->createSubscriptionInvoice($member, $plan);
                $this->dispatch(new SendEventTicketInvoiceJob($member, $invoice->fresh()));
                $member->subscription('cpd')->setInvoiceId($invoice);
                $member->fresh()->subscription('cpd')->suspend();
            }

            $note = new Note([
                'type' => 'subscription_upgrade_procedure',
                'description' => " I have upgraded the client from ".$oldPlan->name." to ".$plan->name." And was confirmed by management and email",
                'logged_by' => $user->first_name .' '.$user->last_name,
            ]);

            if ($plan->price > 0  && !$upgrade->is_practice_plan){
                $note->invoice()->associate($invoice);
            }

            $member->notes()->save($note);
            $upgrade->update(['completed' => true, 'note_id' => $note->id]);

            if ($upgrade->payment_method == 'debit_order'){
                $date = $member->debit->getSubscriptionAndBillableDate();

                $now = Carbon::now();
                $member->subscription('cpd')->starts_at = $now;

                if ($plan->interval == 'month'){
                    $endsat = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year)->addMonth(1);
                }else{
                    $endsat = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year)->addMonth(1)->addYear(1);
                }

                $member->subscription('cpd')->ends_at = $endsat;

                $member->subscription('cpd')->save();
                if ($oldPlan->price <= 0){
                    $member->debit->update(['bill_at_next_available_date' => true]);
                }

                $member->debit->update(['active' => true]);
                $member->debit->update(['billable_date' => $date]);

                $member->debit->next_debit_date = Carbon::now()->addDay(1)->startOfDay();
                $member->debit->save();
            }else{
                    $period = new Period($plan->interval, $plan->interval_count, Carbon::now());
                    $member->subscription('cpd')->starts_at = $period->getStartDate();
                    $member->subscription('cpd')->ends_at = $period->getEndDate();
                    $member->subscription('cpd')->canceled_at = NULL;
                    $member->subscription('cpd')->save();

                if ($member->debit){
                    $member->debit->update([
                        'active' => false
                    ]);
                }
            }

            // Set the agent ID
            if ($member->fresh()->subscription('cpd')->agent_id == null){
                $member->fresh()->subscription('cpd')->setAgent($user);
            }

            // Set the payment method for the user.
            if (! $member->payment_method){
                $member->fresh()->update(['payment_method' => $upgrade->payment_method]);
            }

            Mail::send('emails.upgrades.notify_staff', ['user' => $member, 'note' => $note, 'oldPlan' => $oldPlan, 'newPlan' => $plan], function ($m) {
                $m->from(env('APP_EMAIL'), env('APP_NAME'));
                $m->to(env('APP_TO_EMAIL'))->subject('New Subscription Processed');
            });
        });
    }

    public function createNewUpgrade($user, $member, $newPlan, $oldPlan, $reason, $payment_method, $bf,$is_practice_plan)
    {
        $upgrade = new UpgradeSubscription([
            'user_id' => $user->id,
            'member_id' => $member->id,
            'note_id' => null,
            'new_subscription_package' => $newPlan->id,
            'old_subscription_package' => $oldPlan->id,
            'completed' => false,
            'reason' => $reason,
            'payment_method' => $payment_method,
            'bf' => $bf,
            'is_practice_plan' => $is_practice_plan
        ]);
        $upgrade->save();
        return $upgrade;
    }

    public function setFeatures($features, $upgrade)
    {
        if (! $features == null) {
            foreach ($features as $feature) {
                $newFeature = Feature::findBySlug($feature);
                $toSave = new UpgradeFeatures([
                    'title' => $newFeature->name,
                    'slug' => $newFeature->slug,
                ]);
                $upgrade->features()->save($toSave);
            }
        }
    }

    public function saveComprehensiveTopics($features, $plan, $member)
    {
        $tobeset = collect();
        foreach ($features as $feature){
          $tobeset->push($feature->slug);
        };

        if ($plan->is_custom && !empty($features)) {
            $default = config('byo_default');
            $features = array_merge($tobeset->toArray(), $default);

            CustomPlanFeature::create([
                'user_id' => $member->id,
                'plan_id' => $plan->id,
                'features' => $features
            ]);
        }
    }
}