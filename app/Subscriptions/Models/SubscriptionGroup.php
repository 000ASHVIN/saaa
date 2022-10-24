<?php

namespace App\Subscriptions\Models;

use App\PricingGroup;
use App\AppEvents\Pricing;
use Illuminate\Database\Eloquent\Model;
use App\Subscriptions\Traits\BelongsToPlan;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class SubscriptionGroup extends Model
{
	use BelongsToPlan;

    protected $table = 'subscription_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'admin_id',
        'plan_id',
        'pricing_group_id',
        'subscription_id'
    ];

    public function pricings()
    {
        return $this->belongsTo(PricingGroup::class,'pricing_group_id','id');
    }
}