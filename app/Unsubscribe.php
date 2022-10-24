<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class Unsubscribe extends Model
{
    protected $table = 'unsubscribe';
    protected $guarded = [];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
