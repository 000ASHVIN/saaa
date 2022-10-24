<?php

namespace App\Store\Discounts;

use App\Interfaces\Discount;
use App\Store\Cart;
use App\Store\Listing;
use App\Store\ProductListing;

class PromoCodeDiscount implements Discount
{
    public function getDiscountedItems($cartProductListings, $discount)
    {
        return $cartProductListings;
    }

    public function productListingQualifies($productListing)
    {
        return false;
    }
}