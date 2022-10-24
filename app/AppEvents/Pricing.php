<?php

namespace App\AppEvents;

use App\Body;
use App\Video;
use App\Recording;
use App\Users\User;
use App\Subscriptions\Models\Feature;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\AppEvents\Discounts\PlanDiscount;

/**
 * Class Pricing
 * @package App\AppEvents
 */
class Pricing extends Model
{

    protected $guarded = [];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected $appends = ['discount', 'discounted_price', 'form_help'];

    /**
     * Belongs to Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Has Many Webinars
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function webinars()
    {
        return $this->hasMany(Webinar::class);
    }

    /**
     * Has Webinars ?
     *
     * @return bool
     */
    public function hasWebinars()
    {
        if ($this->webinars && count($this->webinars) > 0)
            return true;

        return false;
    }

    /**
     * Has Many Recordings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recordings()
    {
        return $this->hasMany(Recording::class);
    }

    /**
     * Has Recordings ?
     *
     * @return bool
     */
    public function hasRecordings()
    {
        if ($this->recordings && count($this->recordings) > 0)
            return true;

        return false;
    }

    /**
     * Has many Videos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'recordings');
    }

    /**
     * Belongs to Many Plans
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_pricing_discount');
    }

    /**
     * Has many Discounts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planDiscounts()
    {
        return $this->hasMany(PlanDiscount::class);
    }

    /**
     * Form Helpers
     *
     * @return string
     */
    public function getFormHelpAttribute()
    {
        return $this->attributes['form_help'] = $this->venue->name . ' ' . $this->name . ' ' . $this->description ;
    }

    /**
     * Get Discount
     *
     * @return int|mixed
     */
    public function getDiscountAttribute()
    {
        return ($this->getDiscountForUser()) + ($this->getPromoCodesDiscount()) + ($this->getCPDSubscriberDiscount());
    }

    /**
     * Discounted Pricing
     *
     * @return mixed
     */
    public function getDiscountedPriceAttribute()
    {
        return $this->price - ($this->getDiscountForUser()) - ($this->getPromoCodesDiscount()) - ($this->getCPDSubscriberDiscount());
    }

    public function getCPDSubscriberDiscount(User $user = null) {
        if (! $user){
            $user = auth()->user();
        }

        if($user && $user->subscribed('cpd'))
        {
            if(
                $this->subscription_discount &&
                $user->subscription('cpd')->plan_id != '45' && 
                $this->venue->type != 'online' &&
                Carbon::parse($this->event->start_date)->gt($user->subscription('cpd')->created_at->startOfMonth()->startOfDay())
            ) {
                return $this->subscription_discount;
            }
        }
        return 0;
    }

    /**
     * Has many features
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class);
    }

    /**
     * Fetch Discoutn for User
     *
     * @param User|null $user
     * @return int|mixed
     */
    public function getDiscountForUser(User $user = null)
    {
        if (! $user){
            $user = auth()->user();
        }

        if($user && $user->subscribed('cpd') || $user && $user->hasCompany())
        {
            foreach ($this->features as $feature) {
                if (isset($user->hasCompany()->company)){
                    if(
                        $user->hasCompany()->company->admin()->status == 'active' &&
                        $this->venue->type == 'online' &&
                        !$user->hasCompany()->company->admin()->subscription('cpd')->suspended &&
                        $user->hasCompany()->company->admin()->subscription('cpd')->ability()->canUse($feature->slug) &&
                        Carbon::parse($this->event->start_date)->gt($user->hasCompany()->company->admin()->subscription('cpd')
                            ->created_at->startOfMonth()->startOfDay())
                    ){
                        return $this->price;
                    }else{
                        if(
                            !$user->subscription('cpd')->suspended &&
                            $user->subscription('cpd')->ability()->canUse($feature->slug) &&
                            $user->subscription('cpd')->plan->is_custom && $this->venue->type == 'online' &&
                            Carbon::parse($this->event->start_date)->gt($user->subscription('cpd')->created_at->startOfMonth()->startOfDay())){
                            return $this->price;
                        }elseif(
                            !$user->subscription('cpd')->suspended &&
                            $user->subscription('cpd')->ability()->canUse($feature->slug) &&
                            $user->subscription('cpd')->plan->is_custom != true
                            && Carbon::parse($this->event->start_date)->gt($user->subscription('cpd')->created_at->startOfMonth()->startOfDay())) {
                            return $this->price;
                        } else{
                            continue ;
                        }
                    }
                }else{
                    if(
                        !$user->subscription('cpd')->suspended &&
                        $user->subscription('cpd')->ability()->canUse($feature->slug) &&
                        $user->subscription('cpd')->plan->is_custom && $this->venue->type == 'online' &&
                        Carbon::parse($this->event->start_date)->gt($user->subscription('cpd')->created_at->startOfMonth()->startOfDay())){
                        return $this->price;
                    }elseif(
                        !$user->subscription('cpd')->suspended &&
                        $user->subscription('cpd')->ability()->canUse($feature->slug) &&
                        $user->subscription('cpd')->plan->is_custom != true
                        && Carbon::parse($this->event->start_date)->gt($user->subscription('cpd')->created_at->startOfMonth()->startOfDay())) {
                        return $this->price;
                    } else{
                        continue ;
                    }
                }
            }

        }
        return 0;
    }

    public function getPromoCodesDiscount()
    {
        $totalPromoCodesDiscount = 0;

        $event = $this->event()->with('promoCodes')->first();
        $sessionPromoCodes = PromoCode::sessionCodes();

        if($event) {
            foreach ($event->promoCodes as $promoCode) {
                if (array_has($sessionPromoCodes, $promoCode->code)) {
                    $totalPromoCodesDiscount += calculateDiscount($this->price, $promoCode->discount_amount, $promoCode->discount_type);
                }
            }
        }

        return $totalPromoCodesDiscount;
    }

    public function bodies()
    {
        return $this->belongsToMany(Body::class);
    }

    public function getBodyListAttribute()
    {
        return $this->attributes['body_list'] = $this->bodies->pluck('id')->toArray();
    }

    public function getvenueListAttribute()
    {
        return $this->attributes['venueList'] = $this->venue->id;
    }

    public function actualDate()
    {
        return Carbon::parse($this->venue->dates()->where('is_active',1)->first()->date);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}