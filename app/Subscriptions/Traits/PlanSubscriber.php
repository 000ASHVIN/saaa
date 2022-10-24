<?php

namespace App\Subscriptions\Traits;

use App\Subscriptions\Models\Subscription;
use App\Subscriptions\SubscriptionBuilder;

trait PlanSubscriber {

	/**
     * Get a subscription by name.
     *
     * @param  string $name
     * @return \Gerardojbaez\LaraPlans\Models\Subscription|null
     */
    public function subscription($name = 'cpd')
    {
        return $this->subscriptions->where('name',$name)->sortByDesc(function ($value) {
            return $value->created_at->getTimestamp();
        })
        ->first(function ($value, $key) use ($name) {
            return $key->name === $name;
        });
    }

    /**
     * Get previous CPD subscription.
     *
     * @param  string $name
     * @return \Gerardojbaez\LaraPlans\Models\Subscription|null
     */
    public function previousSubscription()
    {
        // Check if current subscription is ended or canceled
        $subscription = $this->subscription('cpd');
        if($subscription && (!$subscription->active() || $subscription->canceled())) {
            return $subscription;
        }

        // Check deleted subscriptions
        $subscriptions = $this->subscriptions()->onlyTrashed()->get();
        return $subscriptions->where('name', 'cpd')->sortByDesc(function ($value) {
            return $value->created_at->getTimestamp();
        })
        ->first();
    }

    public function activeCPDSubscription() {
        $active_subscription = $this->subscription('cpd');
        if($active_subscription && $active_subscription->active() && !$active_subscription->canceled()) {
            return $active_subscription;
        }
        return null;
    }

    /**
     * Get user plan subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if the user has a given subscription.
     *
     * @param  string $subscription
     * @param  int $planId
     * @return bool
     */
    public function subscribed($subscription = 'default', $planId = null)
    {
        $subscription = $this->subscription($subscription);

        if (is_null($subscription))
            return false;

        if (is_null($planId))
            return $subscription->active();

        if ($planId == $subscription->plan_id && $subscription->active())
            return true;

        return false;
    }


    /**
     * Check if the user has a given subscription.
     *
     * @param  string $subscription
     * @param  int $planId
     * @return bool
     */
    public function isSubscribed($subscription = 'default', $planId = null)
    {
        $subscription = $this->subscriptions()->where('plan_id',$planId)->first();
        

        if (is_null($subscription))
            return false;

        if (is_null($planId))
            return $subscription->active();

        if ($planId == $subscription->plan_id && $subscription->active())
            return true;

        return false;
    }

     /**
     * Check if the user has a given subscription.
     *
     * @param  string $subscription
     * @param  int $planId
     * @return bool
     */
    public function GetSubscription($subscription = 'default', $planId = null)
    {
        $subscription = $this->subscriptions()->where('plan_id',$planId)->first();
        

        if (is_null($subscription))
            return false;

        if (is_null($planId))
            return $subscription->active();

        if ($planId == $subscription->plan_id && $subscription->active())
            return $subscription;

        return false;
    }

    /**
     * Subscribe user to a new plan.
     *
     * @param string $subscription
     * @param mixed $plan
     * @return SubscriptionBuilder
     */
    public function newSubscription($subscription = 'default', $plan)
    {
        // Fire event NewSubscriptionCreated
        return new SubscriptionBuilder($this, $subscription, $plan);
    }

}