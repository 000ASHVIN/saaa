<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table = 'otps';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
