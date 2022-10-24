<?php

namespace App\Subscriptions\Models;

use App\Body;
use App\Profession\Profession;
use Illuminate\Database\Eloquent\Model;
use App\Subscriptions\Contracts\PlanInterface;
use App\Models\Course;
use App\PricingGroup;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use App\Traits\ActivityTrait;

class Plan extends Model implements SluggableInterface, PlanInterface
{
    use SluggableTrait;
    use ActivityTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'interval',
        'interval_count',
        'trial_period_days',
        'sort_order',
        'inactive',
        'cpd_hours',
        'is_practice',
        'is_custom',
        'bf_price',
        'last_minute',
        'enable_bf',
        'invoice_description',
        'package_type',
        'max_no_of_features'
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
    ];
   /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

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
            if ( ! $model->interval)
                $model->interval = 'month';

            if ( ! $model->interval_count)
                $model->interval_count = 1;
        });
    }

    /**
     * Get plan features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class)->orderBy('name');
    }

    /**
     * Get plan subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /*
     * This plan belongs to many bodies
     * $plan->bodies
     */
    public function bodies()
    {
        return $this->belongsToMany(Body::class);
    }

    /**
     * Only Active Subscriptions
     *
     * @return mixed
     */
    public function activeSubscriptions()
    {
        return $this->hasMany(Subscription::class)->active();
    }

    /**
     * Get Interval Name
     *
     * @return mixed string|null
     */
    public function getIntervalNameAttribute()
    {
        $intervals = Period::getAllIntervals();
        return (isset($intervals[$this->interval]) ? $intervals[$this->interval] : null);
    }

    /**
     * Check if plan is free.
     *
     * @return boolean
     */
    public function isFree()
    {
        return ($this->price === 0.00 || $this->price < 0.00);
    }

    /**
     * Check if plan has trial.
     *
     * @return boolean
     */
    public function hasTrial()
    {
        return (is_numeric($this->trial_period_days) && $this->trial_period_days > 0);
    }

    public function getPlanFeaturesListAttribute()
    {
        return $this->features->pluck('id')->toArray();
    }

    public function getProfessionListAttribute()
    {
        return $this->professions->pluck('id')->toArray();
    }

    public function scopeActive($query)
    {
        return $query->where('inactive', false);
    }

    public function getCustomNameAttribute()
    {
        return ucfirst($this->name).' '.ucfirst($this->interval).'ly';
    }

    public function professions()
    {
        return $this->belongsToMany(Profession::class);
    }

    public function suspend()
    {
        $this->update([ 'inactive' => true ]);
        $this->save();
    }
    public function getCourseByPlanId()
    {
        $courses = Course::where('monthly_plan_id',$this->id)->first();
        if($courses)
        {
            return $courses;
        }
        $courses = Course::where('yearly_plan_id',$this->id)->first();
        if($courses)
        {
            return $courses;
        }
        return false;
    }
    public function pricingGroup()
    {
        return $this->belongsToMany(PricingGroup::class,'pricing_group_plan');
    }

    public function getPlanPrice($staff)
    {
        if ($this->pricingGroup->count()) {
            $this->price = $this->price ;
            foreach ($this->pricingGroup as $pricing) {
                if ((int) $pricing->min_user <= $staff && (int) $pricing->max_user >= $staff) {
                    $this->price = $pricing->price ;
                }
            }
            $max = $this->pricingGroup->max('max_user');
            if ($staff > $max) {
                $priceGroup = $this->pricingGroup->where('max_user', $max)->first();
    
                if ($priceGroup) {
                    $this->price = $priceGroup->price ;
                }
            }
            foreach ($this->pricingGroup as $pricing) {
                if ((int) $pricing->min_user < $staff && (int) $pricing->max_user == 0) {
                    $this->price = $pricing->price ;
                }
            }   
        }
    }
    public function getPlanEvents($upcoming = false) {

        $events = collect();
        $user = auth()->user();
        $features = null;
        
        if($user && $this->is_custom) {
            $userPlanFeatures = CustomPlanFeature::where('user_id', $user->id)
                ->where('plan_id', $this->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $feature_slugs = array_flatten($userPlanFeatures->features);
            $planArray = @$this->features->pluck('slug')->toArray();
            $finalArray = array_unique (array_merge($feature_slugs,$planArray));
            $features = Feature::whereIn('slug', $finalArray)
                ->get();
        }
        else {
            $features = $this->features;
        }
        
        if($features) {

            $features->each(function ($feature) use($events){
                if (count($feature->pricings)){
                    $feature->pricings->each(function ($pricing) use ($events){
                        if ($pricing->event){
                            $events->push($pricing->event);
                        }
                    });
                }
            });
        
        }

        if($upcoming) {
            $filtered = $events->filter(function ($event) {
                if($event->is_active && $event->is_open_to_public){
                    if (count($event->venues) > 0 && $event->end_date >= Carbon::now()) {
                        return $event;
                    }
                }
            });
        } else {
            $filtered = $events->filter(function ($event) {
                if($event->is_active && $event->is_open_to_public){
                    if (count($event->venues) > 0 && $event->venues->where('type','online')->count()) {
                        return $event;
                    } elseif ($event->end_date <= Carbon::now()) {
                        return $event;
                    }
                }
            });
        }
    
        return $filtered;
    }

    public function getPlanEventsAll($user) {
        $events = collect();
        $plan = Plan::with('features', 'features.pricings', 'features.pricings.event')->where('id', $this->id)->first();
        $features = $plan->features;
       
        if($user && $this->is_custom) {
            
            $userPlanFeatures = CustomPlanFeature::where('user_id', $user->id)
                ->where('plan_id', $this->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $feature_slugs = $userPlanFeatures ? array_flatten($userPlanFeatures->features) : [];
            $planArray = @$features->pluck('slug')->toArray();
            $finalArray = array_unique (array_merge($feature_slugs,$planArray));
            $features = Feature::with('pricings', 'pricings.event')->whereIn('slug', $finalArray)->get();
        }
        
        if($features) {

            $features->each(function ($feature) use($events){
                if (count($feature->pricings)){
                    $feature->pricings->each(function ($pricing) use ($events){
                        if ($pricing->event){
                            $events->push($pricing->event);
                        }
                    });
                }
            });
        
        }
        return $events;
    }
}
