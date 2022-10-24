<?php

namespace App\Subscriptions\Models;

use Carbon\Carbon;
use App\Users\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Events\SubscriptionRenewed;
use Illuminate\Support\Facades\App;
use App\Subscriptions\Models\Feature;
use Illuminate\Database\Eloquent\Model;
use App\Subscriptions\SubscriptionAbility;
use App\Subscriptions\Models\CustomPlanFeature;
use App\Subscriptions\Contracts\PlanSubscriptionInterface;
use App\Events\CourseSubscriptionRenewed;
use App\Traits\ActivityTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Billing\Invoice;

class Subscription extends Model implements PlanSubscriptionInterface
{
    /**
     * Subscription statuses
     */
    use ActivityTrait;
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_CANCELED = 'canceled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'active',
        'name',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'canceled_at',
        'payment_method',
        'agent_id',
        'invoice_id',
        'completed_semester'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at',
        'canceled_at', 'trial_ends_at', 'ends_at', 'starts_at'
    ];

    /**
     * Subscription Ability Manager instance.
     *
     * @var Gerardojbaez\LaraPlans\SubscriptionAbility
     */
    protected $ability;

    /**
     * Boot function for using with User Events.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            if (! $model->ends_at)
                $model->setNewPeriod();
        });
    }

    /**
     * Get user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Get user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function retrieveCustomFeature($plan, $feature)
    {
        $userPlanFeatures = CustomPlanFeature::where('user_id', $this->user_id)
            ->where('plan_id', $plan->id)
            ->first();

        if(is_null($userPlanFeatures))
            return null;

        if(collect(array_flatten($userPlanFeatures->features))->contains($feature)) {
            return Feature::where('slug', $feature)->first();
        }
        if(collect(array_flatten($this->plan->features->pluck('slug')))->contains($feature)) {
            return Feature::where('slug', $feature)->first();
        }
    }

    /**
     * Retrieve only Active Subscriptions
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->whereNull('canceled_at');
    }

    public function scopeCancelled($query)
    {
        return $query->whereNotNull('canceled_at');
    }

    /**
     * Scope by plan id.
     *
     * @param  Builder
     * @param  int $plan_id
     * @return Builder
     */
    function scopeByPlan($query, $plan_id)
    {
        return $query->where('plan_id', $plan_id);
    }

    /**
     * Get status attribute.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->active())
            return self::STATUS_ACTIVE;

        if ($this->suspended())
            return self::STATUS_SUSPENDED;

        if ($this->canceled())
            return self::STATUS_CANCELED;
    }

    /**
     * Check if subscription is active.
     *
     * @return bool
     */
    public function active()
    {
    	/**
    	 * If the user is suspended then we don't see them as active subscribers
    	 */
//    	if($this->suspended())
//    		return false;

        if (! $this->ended() || $this->onTrial())
            return true;

        return false;
    }

    /**
     * Check if subscription is trialling.
     *
     * @return bool
     */
    public function onTrial()
    {
        if (! is_null($trialEndsAt = $this->trial_ends_at))
            return Carbon::now()->lt(Carbon::instance($trialEndsAt));

        return false;
    }

     /**
     * Check if subscription is suspended.
     *
     * @return bool
     */
    public function suspended()
    {
        return $this->suspended;
    }

    /**
     * Check if subscription is canceled.
     *
     * @return bool
     */
    public function canceled()
    {
        return  ! is_null($this->canceled_at);
    }

    /**
     * Check if subscription period has ended.
     *
     * @return bool
     */
    public function ended()
    {
        $endsAt = Carbon::instance($this->ends_at);

        return Carbon::now()->gt($endsAt) OR Carbon::now()->eq($endsAt);
    }

    /**
     * Suspend subscription.
     *
     * @return $this
     */
    public function unsuspend()
    {
        $this->suspended = false;

        $this->save();

        return $this;
    }

    /**
     * Suspend subscription.
     *
     * @return $this
     */
    public function suspend()
    {
        $this->suspended = true;

        $this->save();

        return $this;
    }

    /**
     * Cancel subscription.
     *
     * @param  bool $immediately
     * @return $this
     */
    public function cancel($immediately = false)
    {
        $this->canceled_at = Carbon::now();

        if ($immediately)
            $this->ends_at = $this->canceled_at;

        $this->save();
        $this->CancelDebitOrder();

        return $this;
    }

    /**
     * Cancel subscription at a given date.
     *
     * @param $date
     * @return $this
     */
    public function cancelAt($date)
    {
        DB::transaction(function () use($date) {
            $this->canceled_at = $date;
            $this->save();

            // Check if this client has Debit Order detais.
            $this->CancelDebitOrder();
        });

        return $this;
    }

    /**
     * Change subscription plan.
     *
     * @param mixed $plan Plan Id or Plan Model Instance
     * @return $this
     */
    public function changePlan($plan)
    {
        if (is_numeric($plan))
            $plan = Plan::find($plan);

        /**
         * If User is on Custom plan we also need to remove the features the user chose
         */
        if($this->plan->is_custom) {
            CustomPlanFeature::where('user_id', $this->user_id)->delete();
        }

        // If plans doesn't have the same billing frequency (e.g., interval
        // and interval_count) we will update the billing dates starting
        // today... and sice we are basically creating a new billing cycle,
        // the usage data will be cleared.
        if (is_null($this->plan) || $this->plan->interval !== $plan->interval ||
                $this->plan->interval_count !== $plan->interval_count)
        {
            // Set period
            $this->setNewPeriod($plan->interval, $plan->interval_count);
        }

        // Attach new plan to subscription
        $this->plan_id = $plan->id;

        return $this;
    }

    /**
     * Renew subscription period.
     *
     * @throws  \LogicException
     * @return  $this
     */
    public function renew()
    {
        $GenerateLastInvoice = $this->canceled_at >= Carbon::now()->startOfDay() && $this->canceled_at <= Carbon::now()->endOfMonth()->endOfDay();
        //  Check if we should generate the last invoice for this subscription.
        if (! $GenerateLastInvoice){
            if ($this->ended() && $this->canceled()){
                throw new \LogicException(
                    'Unable to renew due to canceled and ended subscription.'
                );
            }
        }

        $subscription = $this;

        DB::transaction(function() use ($subscription, $GenerateLastInvoice) {
            if (! $GenerateLastInvoice){
                $isRenewAble = true;
                if($this->plan->interval == 'year')
                {
                    $isRenewAble = $this->user->canRenew();
                }
                // Renew period
                if($isRenewAble){
                $subscription->setNewPeriod();
                }
                $subscription->canceled_at = null;
            }else{
                $subscription->renewable = false;
            }
            if($this->getChildSubscriptions->count()){
                $this->renewChildSubscription($GenerateLastInvoice);
            }
            $subscription->save();
        });

        if($subscription->name == 'cpd'){
            event(new SubscriptionRenewed($this->user, $this->plan,$this));
        }elseif($subscription->name == 'course'){
            event(new CourseSubscriptionRenewed($this->user, $this->plan,$this));
        }

        return $this;
    }

    public function updatePeriod() {
        return $this->setNewPeriod();
    }

    public function renewChildSubscription($GenerateLastInvoice)
    {
        if($this->getActiveSubscriptions()->count())
        {
            foreach($this->getActiveSubscriptions() as $subscription)
            {
                if (! $GenerateLastInvoice){

                    // Renew period
                    if($subscription->user->hasCompany()
                        && $subscription->user->hasCompany()->company->admin()
                    ) {
                        $company_subscription = $subscription->user->hasCompany()->company->admin()->subscription('cpd');
                        $subscription->starts_at = $company_subscription->starts_at;
                        $subscription->ends_at = $company_subscription->ends_at;
                    } else {
                        $subscription->setNewPeriod();
                    }
                    $subscription->canceled_at = null;
                }else{
                    $subscription->renewable = false;
                }
                $subscription->save();
            }
        }
    }

    /**
     * Get Subscription Ability instance.
     *
     * @return \Gerardojbaez\LaraPlans\SubscriptionAbility
     */
    public function ability()
    {
        if (is_null($this->ability))
            return new SubscriptionAbility($this);

        return $this->ability;
    }

    /**
     * Find by user id.
     *
     * @param  Builder
     * @param  int $user_id
     * @return Builder
     */
    public function scopeByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * Find subscription with an ending trial.
     *
     * @return Builder
     */
    public function scopeFindEndingTrial($query, $dayRange = 3)
    {
        $from = Carbon::now();
        $to = Carbon::now()->addDays($dayRange);

        $query->whereBetween('trial_ends_at', [$from, $to]);
    }

    /**
     * Find subscription with an ended trial.
     *
     * @return Builder
     */
    public function scopeFindEndedTrial($query)
    {
        $query->where('trial_ends_at', '<=', date('Y-m-d H:i:s'));
    }

    /**
     * Find renewable subscriptions.
     *
     * @param $query
     * @return void
     */
    public function scopeRenewable($query)
    {
        $query->whereDate('ends_at', '<=', Carbon::today());
            // ->where('renewable', true);
    }

    /**
     * Find the Billable Subscriptions
     * * @param $query
     * * @return void
     **/
    public function scopeBillable($query)
    {
        $query->where('billable', true);
    }

    /*
     * Check if the user subscription is billable
     */
    public function billable()
    {
        return $this->billable;
    }

    /**
     * Find the None Billable Subscriptions
     * * @param $query
     * * @return void
     **/
    public function scopeNotBillable($query)
    {
        $query->where('billable', false);
    }
    
    /**
     * Find ending subscriptions.
     *
     * @return Builder
     */
    public function scopeFindEndingPeriod($query, $dayRange = 3, $from = null)
    {
        $from = $from ?: Carbon::now();
        $to = $from->copy()->addDays($dayRange);

        $query->whereBetween('ends_at', [$from, $to]);
    }

    /**
     * Find ended subscriptions.
     *
     * @return Builder
     */
    public function scopeFindEndedPeriod($query)
    {
        $query->where('ends_at', '<=', date('Y-m-d H:i:s'));
    }

    /**
     * Set subscription period.
     *
     * @param  string $interval
     * @param  int $interval_count
     * @param  string $start Start date
     * @return  $this
     */
    protected function setNewPeriod($interval = '', $interval_count = '', $start = '')
    {
        if (empty($interval))
            $interval = $this->plan->interval;

        if (empty($interval_count))
            $interval_count = $this->plan->interval_count;

        $period = new Period($interval, $interval_count, $start);

        // if ($this->user->payment_method == 'debit_order' && $this->user->debit && $this->plan->interval == 'month'){
        //     $this->starts_at = Carbon::parse($this->ends_at)->startOfDay();
        //     $this->ends_at = Carbon::parse($this->ends_at)->addMonth(1)->startOfDay();
        // }else{
            $this->starts_at = $period->getStartDate();
            $this->ends_at = $period->getEndDate();
        // }

        return $this;
    }

    public function setAgent($agent = null)
    {
        if (! $agent){
            $agent = auth()->user();
        }

        // Set the agent ID to the subscription.
        $this->agent_id = $agent->id;
        $this->save();
    }
    public function setPlanInGroup($true = true)
    {
        $this->plan_in_group = $true;
        $this->save();
    }

    public function SalesAgent()
    {
        return User::find($this->agent_id);
    }

    public function setInvoiceId($invoice)
    {
        $this->update(['invoice_id' => $invoice->id]);
        $this->save();
    }

    private function CancelDebitOrder()
    {
        if ($this->user->debit) {
            $this->user->debit->update([
                'peach' => false,
                'active' => false,
                'otp' => '',
                'bill_at_next_available_date' => false
            ]);
            $this->user->debit->save();
        }
    }
    public function getChildSubscriptions()
    {
        return $this->hasMany(SubscriptionGroup::class,'admin_id','user_id');
    }
    public function getActiveSubscriptions()
    {
        $users = $this->getChildSubscriptions->pluck('user_id')->toArray();
        return Subscription::where('name','cpd')->whereIn('user_id',$users)->where('plan_id',$this->plan_id)->get();
    }

    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
