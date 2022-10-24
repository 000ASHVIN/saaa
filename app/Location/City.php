<?php

namespace App\Location;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $guarded = [];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
