<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;
use App\Subscriptions\Models\Plan;

class UpgradeSubscription extends Model
{
    protected $table = 'subscription_upgrades';
    protected $guarded = [];

    // $upgrade->features
    public function features()
    {
        return $this->hasMany(UpgradeFeatures::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function adminuser()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function member()
    {
        return $this->hasOne(User::class,'id','member_id');
    }
    public function note()
    {
        return $this->hasOne(Note::class);
    }

    public function fromSubscription()
    {
        return $this->hasOne(Plan::class,'id','old_subscription_package');
    }

    public function toSubscription()
    {
        return $this->hasOne(Plan::class,'id','new_subscription_package');
    }
}
