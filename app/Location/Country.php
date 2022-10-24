<?php

namespace App\Location;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $guarded = [];

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
