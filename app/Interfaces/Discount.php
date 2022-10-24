<?php

namespace App\Interfaces;

interface Discount
{
    public function getDiscountedItems($items, $discount);
    public function productListingQualifies($productListings);
}