<?php

namespace App;

use App\AppEvents\Event;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use App\Video;

class Link extends Model
{
    protected $guarded = [];

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::all());
    }

    public function videos()
    {
        return $this->belongsToMany(Video::all());
    }
    
}
