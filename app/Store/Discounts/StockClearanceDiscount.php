<?php

namespace App\Store\Discounts;

use App\Interfaces\Discount;
use App\Store\Cart;
use App\Store\Listing;
use App\Store\ProductListing;

class StockClearanceDiscount implements Discount
{
    public function getDiscountedItems($cartProductListings, $discount)
    {
        $newCartProductListings = collect();
        foreach ($cartProductListings as $cartProductListingKey => $cartProductListing) {
            if ($cartProductListing->qty <= $cartProductListing->product->stock) {
                $newDiscounts = $cartProductListing->globalDiscounts;
                $newDiscounts->put($discount->id, $discount);
                $key = $cartProductListing->id . ':' . implode(';', $newDiscounts->pluck('id')->toArray());
                $newCartProductListings->put($key, new Cart\CartProductListing($cartProductListing->storageItem, $cartProductListing->qty, $newDiscounts));
            } else if ($cartProductListing->product->stock > 0) {
                $undiscountableStockCount = $cartProductListing->qty -= $cartProductListing->product->stock;
                $newCartProductListings->put($cartProductListingKey, new Cart\CartProductListing($cartProductListing->storageItem, $undiscountableStockCount, $cartProductListing->globalDiscounts));
                $newDiscounts = $cartProductListing->globalDiscounts;
                $newDiscounts->put($discount->id, $discount);
                $key = $cartProductListing->id . ':' . implode(';', $newDiscounts->pluck('id')->toArray());
                $newCartProductListings->put($key, new Cart\CartProductListing($cartProductListing->storageItem, $cartProductListing->product->stock, $newDiscounts));
            } else {
                $newCartProductListings->put($cartProductListingKey, $cartProductListing);
            }
        }

        return $newCartProductListings;
    }

    public function productListingQualifies($productListing)
    {
        $storageItem = Cart::getStorageItem($productListing->id);
        if (!$storageItem && $productListing && $productListing->product && $productListing->product->stock > 0)
            return true;

        if ($storageItem && $storageItem->qty < $productListing->product->stock)
            return true;

        return false;
    }
}