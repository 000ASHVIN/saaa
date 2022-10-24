<?php

namespace App\AppEvents;

use App\Assessment;
use App\Blog\Category;
use App\File;
use App\Link;
use App\LiveWebinar;
use App\Presenters\Presenter;
use App\Traits\ActivityTrait;
use App\Subscriptions\Models\Feature;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use MaddHatter\LaravelFullcalendar\IdentifiableEvent;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Traits\SEOTrait;
use App\AppEvents\EventNotification;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event
 * @package App\AppEvents
 */
class Event extends Model implements SluggableInterface, IdentifiableEvent
{
    use SluggableTrait, SearchableTrait,ActivityTrait,SEOTrait,SoftDeletes;

    protected $searchable = [
        'columns' => [
            'name' => 10,
            'description' => 8
        ]
    ];

    protected $Seoname = 'name';

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
        'next_date',
        'start_time',
        'end_time',
        'published_at',
    ];

    /**
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'cpd_hours'
    ];

    protected $hidden = ['description', 'short_description'];

    public function scopeAccountingEvent($query)
    {
        return $query->where('category', 'accounting');
    }

    public function scopeTaxEvent($query)
    {
        return $query->where('category', 'tax');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function venues()
    {
        return $this->belongsToMany(Venue::class);
    }

    public function scopeActiveVenues()
    {
        return $this->venues()->where('is_active', true);
    }

    public function AllVenues()
    {
        return $this->venues();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function presenters()
    {
        return $this->belongsToMany(Presenter::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pricings()
    {
        return $this->hasMany(Pricing::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function upcomingVenue() {
        $upcoming_date = $this->end_date;
		$dates = [];
		$finalvenue = $this->venues()->where('type', 'online')->first();
		
        foreach($this->venues->where('type', 'online') as $venue) {
			foreach($venue->dates()->get() as $date) {
                $finaldate = Carbon::parse($date->attributes['date']);
				 if (Carbon::parse($date->date) == Carbon::today()->startOfDay() || Carbon::parse($date->date) == Carbon::tomorrow()->startOfDay()){
                    $upcoming_date = $finaldate;
					$finalvenue= $venue;
                }
            }
        }
		
        return $finalvenue;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function extras()
    {
        return $this->belongsToMany(Extra::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class);
    }

    public function links()
    {
        return $this->belongsToMany(Link::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }

    public function assessments()
    {
        return $this->belongsToMany(Assessment::class);
    }

    public function promoCodes()
    {
        return $this->belongsToMany(PromoCode::class);
    }

    public function isPast()
    {
        return $this->end_date->lt(Carbon::now());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * $event->features
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class);
    }

    protected function subscriptionEvents()
    {
        return $this->where('subscription_event', true)->where('start_date', '>',  Carbon::now()->startOfYear());
    }

    protected function freePregistrationEvents()
    {
        return $this->where('category', 'free');
    }

    public function getPresenterAttribute()
    {
        return $this->presenters->pluck('id')->toArray();
    }

    public function getStartTimeAttribute()
    {
        $start_time = Carbon::parse($this->attributes['start_time']);
        return $start_time->format('H:i');
    }

    public function getEndTimeAttribute()
    {
        $start_time = Carbon::parse($this->attributes['end_time']);
        return $start_time->format('H:i');
    }

    public function webinars()
    {
        $webinars = collect();
        $this->pricings->each(function ($pricing) use($webinars){
            $pricing->webinars->each(function ($webinar) use($webinars){
               $webinars->push($webinar);
            });
        });
        return $webinars;
    }

    public function getPricings()
    {
        return $this->pricings->first()->features;
    }

    public function liveWebinars()
    {
        return $this->belongsToMany(LiveWebinar::class);
    }

    public function minPrice()
    {
        $pricings = collect();
        $this->pricings()->get()->each(function ($pricing) use ($pricings) {
            if (!$pricing->bodies->count()) {
                $pricings->push($pricing);
            }
        });
        return $pricings->min('price');
    }

    public function getId() {
        return $this->id;
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        return (bool)$this->all_day;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    public function webinarAvailable()
    {
        if (count($this->venues->where('type', 'online')->first()->pricings()->first()->webinars)){
            return true;
        } return false;
    }

    public function getBackgroundUrlAttribute()
    {
        return ($this->attributes['background_url'] ? : "https://imageshack.com/a/img923/6258/ZStfu5.jpg");
    }

    public function getCpdHoursAttribute()
    {
        return number_format(Carbon::parse($this->start_time)->diffInMinutes(Carbon::parse($this->end_time)) / 60, 1, ".", "") + 0;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getTagsAttribute()
    {
        return $this->categories->pluck('id')->toArray();
    }

    // For dynamic meta title
    public function checkMetaTitle()
    {
        $meta_title = '';
        if($this->getMetaTitle()!="") {
            $meta_title = $this->getMetaTitle();
        }
        else {
            $meta_title = $this->attributes['name']  .' | '. config('app.name');
        }
        return $meta_title;
    }
    public function userCanRegister()
    {
        $canRegister = false;
        if($this->reference_id>0)
        {
            
            foreach($this->pricings as $pricing)
            {
                if($pricing->discounted_price == 0)
                {
                    $canRegister = true;
                }
                
            }         
        }else{
            $canRegister = true;
        }
        return $canRegister;
    }
    
    public function upcomingDate() {
        $upcoming_date = $this->end_date;
        foreach($this->venues as $venue) {
            foreach($venue->dates()->where('is_active',true)->get() as $date) {
                $date = Carbon::parse($date->attributes['date']);
                if($date>=Carbon::today() && $date<$upcoming_date) {
                    $upcoming_date = $date;
                }
            }
        }
        return $upcoming_date;
    }

    public function getNotificationStatusTextAttribute() {

        $statuses = [
            'not_scheduled' => 'Not Scheduled',
            'in_progress' => 'In Progress',
            'scheduled' => 'Scheduled',
            'completed' => 'Completed'
        ];

        $status = isset($statuses[$this->notification_status])?$statuses[$this->notification_status]:'-';
        return $status;
        
    }

    public function notifications() {
        return $this->hasOne(EventNotification::class, 'event_id');
    }
    public function getWebinar($user)
    {
        try{
        $ticket = $this->tickets()->where('event_id', $this->id)->where('user_id', $user->id)->first();
        $webinars =  ($ticket->pricing->videos)?$ticket->pricing->videos->pluck('id')->toArray():[];
        foreach($webinars as $webinar)
        {
            if($user)
            {
                if(!$user->webinars->contains($webinar))
                {
                    $user->webinars()->attach($webinar);
                }
            }
       }
        }catch(\exception $e)
        {
            
        }
    }

}