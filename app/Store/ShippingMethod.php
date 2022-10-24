<?php

namespace App\Store;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $table = 'store_shipping_methods';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return bool
     */
    public static function cartHasPhysicalProduct()
    {
        $cartProductListings = ProductListing::allFromCart();
        $hasShippingFee = false;
        foreach ($cartProductListings as $cartProductListing) {
            if ($cartProductListing->product->is_physical) {
                $hasShippingFee = true;
                break;
            }
        }

        return $hasShippingFee;
    }
}
