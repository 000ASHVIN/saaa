<?php

namespace App\AppEvents\Discounts;

use App\Users\User;
use App\AppEvents\Pricing;
use App\Subscriptions\Models\Plan;
use Illuminate\Database\Eloquent\Model;

class PlanDiscount extends Model
{
    protected $table = 'plan_pricing_discount';

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }
}
