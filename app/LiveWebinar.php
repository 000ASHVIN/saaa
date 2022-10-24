<?php

namespace App;

use App\AppEvents\Event;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class LiveWebinar extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

    protected $table = 'live_webinars';
    protected $guarded = [];

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function getEventAttribute()
    {
        return $this->events->pluck('id')->toArray();
    }

}
