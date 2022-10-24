<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PricingGroup extends Model 
{
    protected $table = 'pricing_group';
    protected $fillable = [
        'name',
        'price',
        'min_user',
        'max_user',
        'billing',
        'is_active'
    ];
    protected $dates = ['created_at', 'updated_at']; 
} 