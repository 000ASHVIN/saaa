<?php

namespace App\Store;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'store_discounts';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['instance', 'detailedTitle'];

    public static function getTotalOfAllDiscountsFromCart($stack = true)
    {
        $totalDiscount = 0;
        $discounts = static::active()->get();
        foreach ($discounts as $discount) {
            $discountInstance = app($discount->model);
            $totalAmount = $discountInstance->getTotalValueOfQualifyingItems($stack);
            $totalDiscount += static::getDiscountAmount($totalAmount, $discount->value, $discount->type);
        }
        return $totalDiscount;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getDiscountAmount($total, $value, $type)
    {
        if ($type === 'percentage')
            $amount = calculateDiscount($total, $value . '%');
        else
            $amount = $value;

        return $amount;
    }

    public function scopeGlobalDiscounts($query)
    {
        return $query->where('is_global', true);
    }

    public function getInstanceAttribute()
    {
        return app($this->model);
    }

    public function listings()
    {
        return $this->belongsToMany(Listing::class, 'store_discount_store_listing');
    }

    public function getDetailedTitleAttribute()
    {
        return $this->title . ' (' . discountString($this->value, $this->type) . ')';
    }
}