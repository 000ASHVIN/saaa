<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
