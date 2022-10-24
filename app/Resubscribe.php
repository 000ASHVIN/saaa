<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class Resubscribe extends Model
{
    protected $table = 'resubscribe';
    protected $guarded = [];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
