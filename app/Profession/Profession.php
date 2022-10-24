<?php

namespace App\Profession;

use App\Body;
use App\Subscriptions\Models\Plan;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use App\SponsorList;

class Profession extends Model implements SluggableInterface
{
    use SluggableTrait;
    protected $table = 'professions';
    protected $guarded = [];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class)->orderBy('price');
    }

    // Return the plus for this package.
    public function plus()
    {
        return $this->plans()->where('price', '>' ,'250')->first();
    }

    public function bodies()
    {
        return $this->belongsToMany(Body::class);
    }

    public function getBodyListAttribute()
    {
        return $this->bodies->pluck('id')->toArray();
    }

    public function sponsors()
    {
        return $this->belongsToMany(SponsorList::class, 'profession_sponsor', 'profession_id', 'sponsor_id');
    }

    public function getSponsorListAttribute()
    {
        return $this->sponsors->pluck('id')->toArray();
    }
}
