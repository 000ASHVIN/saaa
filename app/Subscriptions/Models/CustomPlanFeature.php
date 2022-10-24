<?php

namespace App\Subscriptions\Models;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property array $features
 */
class CustomPlanFeature extends Model
{
    protected $table = 'custom_plan_features';

    protected $guarded = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'features' => 'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
