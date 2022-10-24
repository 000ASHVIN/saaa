<?php

namespace App;

use App\Subscriptions\Plan;
use Illuminate\Database\Eloquent\Model;

class InstallmentConfig extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }
}
