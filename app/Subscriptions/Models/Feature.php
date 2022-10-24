<?php

namespace App\Subscriptions\Models;

use App\AppEvents\Pricing;
use Illuminate\Database\Eloquent\Model;
use App\Subscriptions\Traits\BelongsToPlan;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use App\Video;
use App\Subscriptions\Models\Plan;
use App\Traits\ActivityTrait;

class Feature extends Model implements SluggableInterface
{
    use SluggableTrait;
    use BelongsToPlan;
    use ActivityTrait;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
    ];
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'slug',
        'name',
        'description',
        'sort_order',
        'value',
        'selectable',
        'year'
    ];

    public function pricings()
    {
        return $this->belongsToMany(Pricing::class);
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }

}