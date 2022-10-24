<?php

namespace App\AppEvents;

use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    protected $guarded = [];

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class);
    }
}
