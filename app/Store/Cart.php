<?php

namespace App\Store;

use App\Billing\Item;
use App\Store\Cart\CartProductListing;
use App\Store\Cart\SimpleCartStorageItem;

class Cart
{
    protected static $cookie_prefix = 'qDtH1taQJruZtxqR';
    protected static $cookie_name = '';

    public static function addProductListing($productListing)
    {
        $productListingId = $productListing->id;
        if(request()->venue){
            $productListingId = request()->venue."-".$productListingId;
        }
        $storageItemsArray = self::storageItemsArray();
        if (array_key_exists($productListingId, $storageItemsArray))
            $storageItemsArray[$productListingId]->qty += 1;
        else
            $storageItemsArray[$productListingId] = new SimpleCartStorageItem($productListingId,get_class($productListing));
        return self::storageItemsArray($storageItemsArray);
    }

    public static function getStorageItem($storageItemId)
    {
        return self::getAllStorageItems()->get($storageItemId);
    }

    public static function getAllStorageItems()
    {
        return collect(session(self::cookieName()));
    }

    public static function getAllCartProductListings()
    {
        return self::generateCartProductListingCollection();
    }

    public static function getAllProductListings()
    {
        $storageItemIds = self::getAllStorageItems()->pluck('id');
        return ProductListing::whereIn('product_id', $storageItemIds)->with(['listing', 'listing.discounts', 'product'])->get();
    }

    public static function getAllInvoiceItems()
    {
        return self::generateInvoiceLineItems();
    }

    public static function removeProductListing($productListingId)
    {
        return session()->forget(self::cookieName() . '.' . $productListingId);
    }

    public static function removeProductListingWithQty($productListingId, $qty)
    {
        if (self::getStorageItem($productListingId)->qty <= $qty)
            return self::removeProductListing($productListingId);

        $storageItemsArray = self::storageItemsArray();
        $storageItemsArray[$productListingId]->qty -= $qty;
        return self::storageItemsArray($storageItemsArray);
    }

    public static function updateProductListingQty($productListingId, $qty)
    {
        if ($qty <= 0)
            return self::removeProductListing($productListingId);

        $storageItemsArray = self::storageItemsArray();
        $storageItemsArray[$productListingId]->qty = $qty;
        return self::storageItemsArray($storageItemsArray);
    }

    public static function clear()
    {
        return session()->forget(self::cookieName());
    }

    public static function hasPhysicalProduct()
    {
        $hasPhysicalProduct = false;
        $cartProductListings = self::getAllCartProductListings();
        foreach ($cartProductListings as $cartProductListing) {
            if ($cartProductListing->isPhysical) {
                $hasPhysicalProduct = true;
                break;
            }
        }
        return $hasPhysicalProduct;
    }

    public static function getTotalQuantity()
    {
        $totalQty = 0;
        $simpleStorageItems = self::getAllStorageItems();
        foreach ($simpleStorageItems as $item) {
            $totalQty += $item->qty;
        }
        return $totalQty;
    }

    public static function getTotalCPDHours()
    {
        $totalCPDHours = 0;
        $cartProductListings = self::getAllCartProductListings();
        foreach ($cartProductListings as $cartProductListing) {
            $totalCPDHours += $cartProductListing->CPDHours;
        }
        return $totalCPDHours;
    }

    public static function getAllUniqueProductTags()
    {
        $uniqueTags = collect([]);
        $cartProductListings = self::getAllCartProductListings();
        foreach ($cartProductListings as $cartProductListing) {
            $tags = $cartProductListing->product->tags;
            foreach ($tags as $tag) {
                if (!$uniqueTags->has($tag->id)) {
                    $uniqueTags->put($tag->id, $tag);
                }
            }
        }
        return $uniqueTags;
    }

    public static function getTotalPrice()
    {
        $totalPrice = 0;
        $cartProductListings = self::getAllCartProductListings();
        foreach ($cartProductListings as $cartProductListing) {
            $totalPrice += ($cartProductListing->price * $cartProductListing->qty);
        }
        return $totalPrice;
    }

    public static function getTotalDiscountedPrice()
    {
        return self::getTotalPrice() - self::getTotalDiscount();
    }

    public static function getTotalDiscount()
    {
        $totalDiscount = 0;
        $cartProductListings = self::getAllCartProductListings();
        foreach ($cartProductListings as $cartProductListing) {
            $totalDiscount += ($cartProductListing->discountAmount * $cartProductListing->qty);
        }
        return $totalDiscount;
    }

    public static function assignAllProductListingsToUser($user, $orderInvoice = null, $address = null, $shippingMethod = null)
    {
        $cartProductListings = self::getAllCartProductListings();
        foreach ($cartProductListings as $productListing) {
            if (! $orderInvoice)
                $existingOrderForProduct = $user->orders()->where('product_id', $productListing->product->id)->first();
            else
                $existingOrderForProduct = $user->orders()->where('product_id', $productListing->product->id)->where('invoice_order_id', $orderInvoice->id)->first();
            if ($existingOrderForProduct && count($existingOrderForProduct) > 0) {
                $existingOrderForProduct->quantity += $productListing->qty;
                $existingOrderForProduct->save();
                if($productListing->model == 'ProductListing'){
                    Product::where('id', $productListing->product->id)->decrement('stock', $productListing->qty);
                }
            } else {
                $order = new Order();
                $order->setUser($user);
                $order->setProduct($productListing->product);
                if($productListing->model == 'ProductListing'){
					$order->setListing($productListing->listing);
				}
                if ($shippingMethod)
                    $order->setShippingMethod($shippingMethod);
                if ($orderInvoice)
                    $order->setInvoiceOrder($orderInvoice);
                $order->quantity = $productListing->qty;
                $order->save();
                if($productListing->model == 'ProductListing'){
                    Product::where('id', $productListing->product->id)->decrement('stock', $productListing->qty);
                    if ($productListing->product->is_physical && $address) {
                        $shippingInformation = ShippingInformation::createFromAddress($address);
                        $order->setShippingInformation($shippingInformation);
                        $order->save();
                    }
                }
            }
        }
    }

    private static function cookieName()
    {
        return self::$cookie_prefix . '_' . static::$cookie_name;
    }

    private static function updateStorageItemsArray($storageItemsArray)
    {
        return session()->put(self::cookieName(), $storageItemsArray);
    }

    private static function storageItemsArray($storageItemsArray = null)
    {
        if ($storageItemsArray)
            return self::updateStorageItemsArray($storageItemsArray);

        return self::getAllStorageItems()->toArray();
    }

    private static function getAllUniqueDiscountsForCartProductListings()
    {
        $uniqueDiscounts = collect([]);
        $productListings = self::getAllProductListings();
        foreach ($productListings as $productListing) {
            $productListingDiscounts = ($productListing->listing)?$productListing->listing->discounts()->get():collect();
            foreach ($productListingDiscounts as $productListingDiscount) {
                if (!$uniqueDiscounts->has($productListingDiscount->id))
                    $uniqueDiscounts->put($productListingDiscount->id, $productListingDiscount);
            }
        }
        $globalDiscounts = Discount::globalDiscounts()->active()->get();
        foreach ($globalDiscounts as $globalDiscount) {
            $uniqueDiscounts->put($globalDiscount->id, $globalDiscount);
        }
        return $uniqueDiscounts;
    }

    private static function generateInitialCartProductListingCollection()
    {
        $cartProductListings = collect([]);
        $storageItems = self::getAllStorageItems();
        foreach ($storageItems as $storageItem) {
            $cartProductListings->put(
                $storageItem->id,
                new CartProductListing($storageItem, $storageItem->qty)
            );
        }
        return $cartProductListings;
    }

    private static function generateCartProductListingCollection()
    {
        $initialCartProductListings = self::generateInitialCartProductListingCollection();
        $discounts = self::getAllUniqueDiscountsForCartProductListings();
        $cartProductListings = $initialCartProductListings;
        foreach ($discounts as $discount) {
            $cartProductListings = $discount->instance->getDiscountedItems($cartProductListings, $discount);
        }

        foreach ($cartProductListings as $cartProductListing) {
            $cartProductListing->compileAndCompute();
        }

        return $cartProductListings->sortBy('title');
    }

    private static function generateInvoiceLineItems()
    {
        $invoiceLineItems = [];
        $cartProductListings = self::getAllCartProductListings();
        foreach ($cartProductListings as $cartProductListing) {
            $invoiceLineItems[] = Item::create($cartProductListing->toInvoiceItemArray());
        }
        return $invoiceLineItems;
    }
}