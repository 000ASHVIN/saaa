<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoProvider extends Model
{
    protected $appends = ['instance'];
    public $videos = [];

    public function getInstanceAttribute()
    {
        $instance = app($this->model);
        $instance->attributes = $this->attributes;
        return $instance;
    }
}
