<?php

namespace App\Traits;

use App\Store\Cart;

trait IsCartable
{
    /**
     * @return void
     */
    public function addToCart()
    {
        Cart::addProductListing($this);
    }
}