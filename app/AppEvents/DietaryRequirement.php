<?php

namespace App\AppEvents;

use Illuminate\Database\Eloquent\Model;

class DietaryRequirement extends Model
{
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
