<?php

namespace App;

use App\AppEvents\Pricing;
use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
