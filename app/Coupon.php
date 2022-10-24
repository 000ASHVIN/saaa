<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'name',
        'description',
        'code',
        'expiry_date',
        'discount_type',
        'discount'
    ];

    protected $dates = ['expiry_date'];

    /**
     * @param $amount
     * @return float|mixed
     */
    public function getDiscount($amount)
    {
        if($this->discount_type == 'percentage')
            return (($this->discount / 100) * $amount);

        return $this->discount;
    }
}
