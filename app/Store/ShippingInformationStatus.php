<?php

namespace App\Store;

use Illuminate\Database\Eloquent\Model;

class ShippingInformationStatus extends Model
{
    protected $table = 'store_shipping_information_statuses';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shippingInformationStatus) {
            if (!$shippingInformationStatus->slug)
                $shippingInformationStatus->slug = str_slug($shippingInformationStatus->title);
        });
    }

    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    public function shippingInformation()
    {
        return $this->hasMany(ShippingInformation::class);
    }
}
