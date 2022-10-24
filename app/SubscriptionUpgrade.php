<?php

namespace App;

use App\Subscriptions\Models\Plan;
use App\Users\User;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class SubscriptionUpgrade extends Model
{
    use SearchableTrait;
    protected $guarded = [];
    protected $table = 'upgrade_subscriptions';

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class,'plan_id','id');
    }
    public function newplan()
    {
        return $this->belongsTo(Plan::class,'new_plan_id','id');
    }
}
