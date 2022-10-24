<?php

namespace App\Store;

use App\Users\Address;
use Illuminate\Database\Eloquent\Model;

class ShippingInformation extends Model
{
    protected $table = 'store_shipping_information';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shippingInformation) {
            $shippingInformation->status_id = ShippingInformationStatus::findBySlug('awaiting-payment')->id;
        });
    }

    public static function createFromAddress($address)
    {
        $shippingInformation = ShippingInformation::create([]);
        $shippingInformation->setAddress($address);
        return $shippingInformation;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function setAddress($address)
    {
        return $this->address()->associate($address);
    }

    public function status()
    {
        return $this->belongsTo(ShippingInformationStatus::class);
    }

    public function updateStatusBySlug($slug)
    {
        $newStatus = ShippingInformationStatus::findBySlug($slug);
        $this->status()->associate($newStatus);
    }
}
