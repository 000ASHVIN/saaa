<?php

namespace App;

use App\Blog\Category;
use App\Traits\SEOTrait;
use App\AppEvents\Pricing;
use App\Users\User;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Store\Cart;
use App\Subscriptions\Models\Feature;
use App\Presenters\Presenter;
use App\Assessment;
use App\Link;
use App\Traits\ActivityTrait;
use App\Users\Cpd;

class Video extends Model implements SluggableInterface
{
    use SluggableTrait, SearchableTrait,SEOTrait,ActivityTrait;

    protected $Seoname = 'title';

    protected $searchable = [
        'columns' => [
            'title' => 10,
            'description' => 8,
        ]
    ];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['event'];

    protected $hidden = ['description'];

    public function videoProvider()
    {
        return $this->belongsTo(VideoProvider::class);
    }

    public function pricings()
    {
        return $this->belongsToMany(Pricing::class, 'recordings');
    }

    public function recordings()
    {
        return $this->hasMany(Recording::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function getEventAttribute()
    {
        if (count($this->pricings) >= 1){
            return $this->pricings->first()->event;
        }
        return '#';
    }

    public function gethoursAttribute() {
        if($this->type == 'series') {
            $hours = 0;
            $series_webinars = $this->webinars;
            foreach($series_webinars as $value) {
                $hours = $hours + $value->hours;
            }
            $this->attributes['hours'] = $hours;
        }
        return $this->attributes['hours'];
    }

    public function getamountAttribute($discounted=true)
    {
        if($discounted===null) {
            $discounted=true;
        }

        $user = auth()->user();
        $url = \Request::getRequestUri();
        $isAdmin = false;
        if($this->type != 'series') {
            // if (strpos($url, 'admin') !== false) {
            //     $isAdmin=true;
            // }
            // if ($user && $user->subscribed('cpd') && $user->subscription('cpd')->plan->price != '0'){
            //     if( isset($this->attributes['amount']) && $this->attributes['amount'] && $this->attributes['amount'] >= 100 && !$isAdmin && env('APP_THEME') == 'saaa')
            //     {
            //         return $this->attributes['amount'];// - 100;   
            //     }
            // } else{
            //     return $this->attributes['amount'];
            // }
            return $this->attributes['amount'];

        } else {
            $series_webinars = $this->webinars;
            $amount = 0;
            $discounted_price = 0;
            
            // User's owned webinars
            $owned_webinars = [];
            if($user) {
                $owned_webinars = $user->webinars->pluck('id')->toArray();
            }

            // If fixed price series
            if($this->fix_price_series) {
                $amount = $this->attributes['amount'];
            }
            else {
                // If not admin screen and also not owned series
                if (strpos($url, 'admin') == false && !in_array($this->id,$owned_webinars)) {  

                    // Calculate amount only of not owned webinars
                    foreach($series_webinars as $value) {
                        if(!in_array($value->id,$owned_webinars)) {
                            $amount = $amount + $value->getOriginal('amount');
                        } 
                    }
    
                } else {
                    foreach($series_webinars as $value) {
                        $amount = $amount + $value->getOriginal('amount');
                    }
                }
            }

            
            // Apply discount
            if($discounted && $amount!=0) {
                $discount = $this->attributes['discount'];
                $discounted_price = $amount - (($amount * $discount) / 100);
                // $this->attributes['amount'] = $discounted_price;
                $amount = $discounted_price;
            }
            else {
                // $this->attributes['amount'] = $amount;
            }
            return $amount;
        }       
        return $this->attributes['amount'];
    }

    public function getOriginalAmountAttribute()
    {
        return $this->getamountAttribute(false);
    }

    public function getAmountForUser($user) {
        
        if($this->type == 'series') {
            
            $series_webinars = $this->webinars;
            $amount = 0;
            
            // User's owned webinars
            $owned_webinars = [];
            if($user) {
                $owned_webinars = $user->webinars->pluck('id')->toArray();
            }

            // Calculate amount only of not owned webinars
            foreach($series_webinars as $value) {
                if(!in_array($value->id,$owned_webinars)) {
                    $amount = $amount + $value->getOriginal('amount');
                } 
            }
            
            // Apply discount
            if($amount!=0) {
                $discount = $this->attributes['discount'];
                $discounted_price = $amount - (($amount * $discount) / 100);
                $this->attributes['amount'] = $discounted_price;
            }

        }       
        return $this->attributes['amount'];
    }

    public function categories()
    {
        return $this->belongsTo(Category::class,'category','id'); 
    }

    public function webinars() {
        return $this->belongsToMany('App\Video', 'series_videos', 'series_id', 'video_id')->withPivot('sequence');
    }

    public function webinar_series() {
        return $this->belongsToMany('App\Video', 'series_videos', 'video_id','series_id');
    }

    public function getWebinarsCountAttribute() {
        return $this->webinars()->count();
    }

    public function getWebinarSequenceAttribute() {
        $sequence = 1;
        $last_webinar = $this->webinars()->orderBy('series_videos.sequence','desc')->first();

        if($last_webinar) {
            $sequence = $last_webinar->pivot->sequence;
            $sequence++;
        }
        return $sequence;
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class);
    }

    public function presenters()
    {
        return $this->belongsToMany(Presenter::class);
    }

    public function getVideoFeaturesListAttribute()
    {
        
        return $this->features->pluck('id')->toArray();
    }
    public function checkMetaTitle()
    {
        $meta_title = '';
        if($this->getMetaTitle()!="") {
            $meta_title = $this->getMetaTitle();
        }
        else {
            $meta_title = $this->attributes['title']  .' | Webinars On-Demand | '. config('app.name');
        }
        return $meta_title;
    }

    public function category()
    {
        return Category::where('id', $this['category']);
    }

    public function categoryImage()
    {
        $category = Category::where('id', $this['category'])->first();
        if($category) {
            if($category->parent_id == 0 || $category->parent_id == '0') {
                return $category->image;
            } else {
                if($category->image != '' && $category->image != null) {
                    return $category->image;
                }
                else {
                    $parent = Category::find($category->parent_id);
                    if($parent->parent_id == 0 || $parent->parent_id == '0') {
                        return $parent->image;
                    } else {
                        $supParent = Category::find($parent->parent_id);
                        return $supParent->image;
                    }
                }
            }
        } else {
            return "";
        }
    }

    public function videoInCart()
    {
        return Cart::getStorageItem($this->id);
    }

    public function addToCart()
    {
        Cart::addProductListing($this);
    }

    public function removeFromCart($id)
    {
        Cart::removeProductListing($id);
    }

    public function assessments()
    {
        return $this->belongsToMany(Assessment::class, 'assessment_video');
    }

    public function links()
    {
        return $this->belongsToMany(Link::class, 'video_link');
    }

    public function calculateWebinarComplete($user) {

        $video = $this;
        $webinar_complete = 1;
        if($user && $video->type == 'single') {

            // Check cpd claimed or not
            if($video->view_resource && $video->hours>0) {

                $cpd = Cpd::where('video_id', $video->id)
                    ->where('user_id', $user->id)
                    ->get();
                if(!$cpd->count()) {
                    $webinar_complete = 0;
                }

            }

            // Check assessments completed or not
            $assessments = $video->assessments;
            if(count($assessments)) {
                foreach($assessments as $assessment) {
                    if(!$assessment->hasBeenPassedByUser($user)){
                        $webinar_complete = 0;
                    }
                }
            }

            $user->webinars()->updateExistingPivot($video->id, [
                'webinar_complete' => $webinar_complete
            ]);

        }

    }

}
