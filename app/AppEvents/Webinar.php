<?php

namespace App\AppEvents;

use App\AppEvents\Pricing;
use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{

    protected $guarded = [];

    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }
}
