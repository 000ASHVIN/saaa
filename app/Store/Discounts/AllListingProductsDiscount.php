<?php

namespace App\Store\Discounts;

use App\Interfaces\Discount;
use App\Store\Cart;
use App\Store\Listing;
use App\Store\ProductListing;

class AllListingProductsDiscount implements Discount
{
    protected $discount;

    public function getDiscountedItems($cartProductListings, $discount)
    {
        $this->discount = $discount;
        $uniqueListings = $this->getAllUniqueListings($cartProductListings);
        $flattenedProductListings = $this->getFlattenedProductListings($cartProductListings);
        $groupsOfProductListings = $this->makeListingGroups($uniqueListings, $flattenedProductListings, $cartProductListings);

        $discountItemsWithDiscount = $this->createDiscountItemsFromProductListingGroups($groupsOfProductListings);

        $discountItemsWithoutDiscount = $this->createDiscountItemsFromLeftOverProductListings($flattenedProductListings);

        $newCartProductListings = collect([]);
        $flattenedProductListings = $this->getFlattenedProductListings($cartProductListings);
        $consumedProductListings = collect([]);
        foreach ($discountItemsWithDiscount as $discountItem) {
            $foundProductListings = collect([]);
            foreach ($flattenedProductListings as $flattenedProductListingKey => $flattenedProductListing) {
                if ($consumedProductListings->has($flattenedProductListingKey))
                    continue;

                if ($discountItem['productListingId'] == $flattenedProductListing->id) {
                    $foundProductListings->put($flattenedProductListingKey, $flattenedProductListing);
                }
            }
            if (count($foundProductListings) >= $discountItem['qty']) {
                foreach ($foundProductListings as $foundProductListingKey => $foundProductListing) {
                    $consumedProductListings->put($foundProductListingKey, $foundProductListing);
                    $newGlobalDiscounts = $foundProductListing->globalDiscounts;
                    $newGlobalDiscounts->put($this->discount->id, $this->discount);
                    $key = $foundProductListing->id . ':' . implode(';', $newGlobalDiscounts->pluck('id')->toArray());
                    if (!$newCartProductListings->has($key))
                        $newCartProductListings->put($key, new Cart\CartProductListing($foundProductListing->storageItem, $discountItem['qty'], $newGlobalDiscounts));
                    else {
                        $existingNewCartProductListing = $newCartProductListings->get($key);
                        $existingNewCartProductListing->qty += $discountItem['qty'];
                    }
                    break;
                }
            }
        }

        foreach ($discountItemsWithoutDiscount as $discountItemWithoutDiscount) {
            $newCartProductListings->put($discountItemWithoutDiscount['productListingId'], new Cart\CartProductListing(new Cart\SimpleCartStorageItem($discountItemWithoutDiscount['productListingId'], $discountItemWithoutDiscount['qty']), $discountItemWithoutDiscount['qty'], []));
        }

        //$mergedDiscountItems = array_merge($discountItemsWithDiscount->toArray(), $discountItemsWithoutDiscount->toArray());
        //return $mergedDiscountItems;

        return $newCartProductListings;
    }

    //Get all of the unique listings in the cart storage items.
    //This is to allow us to easily check for each listing how many times it has all its products in the flattenedStorageItems below.
    private function getAllUniqueListings($cartProductListings)
    {
        $uniqueListings = collect([]);
        foreach ($cartProductListings as $cartProductListing) {
            $listing = $cartProductListing->listing;
            if (!$uniqueListings->has($listing->id))
                $uniqueListings->put($listing->id, $listing);
        }

        return $uniqueListings;
    }

    //Flatten storage by repeating each storage item for its quantity.
    //This is to make finding groups easier by removing them from the array after matching.
    private function getFlattenedProductListings($cartProductListings)
    {
        $flattenedProductListings = collect([]);
        foreach ($cartProductListings as $cartProductListing) {
            for ($qtyCount = 0; $qtyCount < $cartProductListing->qty; $qtyCount++)
                $flattenedProductListings->push($cartProductListing);
        }

        return $flattenedProductListings;
    }

    private function makeListingGroups($uniqueListings, $flattenedProductListings)
    {
        //Loop through all the listings in the cart and create groups from the flattenedStorageItems,
        //removing all full listings from the flattenedStorageItems array
        $groupsOfProductListings = collect([]);
        foreach ($uniqueListings as $listing) {
            $consumedProductListings = collect([]);
            $listingProductListings = $listing->productListings;
            $qualifiedProductListingsInListingTargetCount = count($listingProductListings);
            $groupsToMake = 0;
            do {
                $foundGroup = false;
                $qualifiedProductListingsInListing = collect([]);
                foreach ($listingProductListings as $listingProductListingKey => $listingProductListing) {
                    foreach ($flattenedProductListings as $flattenedProductListingKey => $flattenedProductListing) {
                        if ($consumedProductListings->has($flattenedProductListingKey))
                            continue;
                        if ($flattenedProductListing->id == $listingProductListing->id) {
                            $qualifiedProductListingsInListing->put($flattenedProductListingKey, $flattenedProductListing);
                            break;
                        }
                    }
                }
                if (count($qualifiedProductListingsInListing) == $qualifiedProductListingsInListingTargetCount) {
                    foreach ($qualifiedProductListingsInListing as $qualifiedProductListingInListingKey => $qualifiedProductListingInListing) {
                        $consumedProductListings->put($qualifiedProductListingInListingKey, $qualifiedProductListingInListing);
                    }
                    $groupsToMake++;
                    $foundGroup = true;
                }
            } while ($foundGroup);

            //Loop through all the qualifying items and remove them from the flattenedStorageItemsArray
            //if all of the items in the listing are in the flattenedStorageItemsArray (cart)
            if ($groupsToMake > 0) {
                foreach ($listingProductListings as $listingProductListing) {
                    for ($i = 0; $i < $groupsToMake; $i++) {
                        foreach ($flattenedProductListings as $flattenedProductListingKey => $flattenedProductListing) {
                            if ($flattenedProductListing->id == $listingProductListing->id) {
                                $flattenedProductListings->forget($flattenedProductListingKey);
                                break;
                            }
                        }
                    }
                }
                if ($groupsOfProductListings->has($listing->id)) {
                    $updatedGroupsToMake = $groupsOfProductListings->get($listing->id);
                    $updatedGroupsToMake += $groupsToMake;
                    $groupsOfProductListings->put($listing->id, $updatedGroupsToMake);
                } else {
                    $groupsOfProductListings->put($listing->id, $groupsToMake);
                }
            }
        }

        return $groupsOfProductListings;
    }

    //Loop through all groups and create CartProductListings with the correct qty and global discount
    private function createDiscountItemsFromProductListingGroups($groupsOfProductListings)
    {
        $discountItems = collect([]);
        foreach ($groupsOfProductListings as $listingId => $groupOfQualifyingProductListings) {
            $listing = Listing::findOrFail($listingId);
            $listingProductListings = $listing->productListings;
            foreach ($listingProductListings as $listingProductListing) {
                $discountItems->push([
                    'productListingId' => $listingProductListing->id,
                    'qty' => $groupOfQualifyingProductListings,
                    'applyDiscount' => true
                ]);
            }
        }
        return $discountItems;
    }

    //Loop through all remaining (ungrouped) storageItems and create CartProductListings with correct qty
    private function createDiscountItemsFromLeftOverProductListings($flattenedProductListings)
    {
        $discountItems = collect([]);
        $raisedStorageItems = collect([]);
        foreach ($flattenedProductListings as $key => $flattenedProductListing) {
            $qty = 0;
            foreach ($flattenedProductListings as $comparisonKey => $flattenedProductListingComparison) {
                if ($key == $comparisonKey)
                    continue;
                if ($flattenedProductListing == $flattenedProductListingComparison) {
                    if ($raisedStorageItems->has($flattenedProductListing->id))
                        $qty = $raisedStorageItems->get($flattenedProductListing->id);
                }
            }
            $raisedStorageItems->put($flattenedProductListing->id, $qty + 1);
        }

        foreach ($raisedStorageItems as $raisedStorageItemKey => $raisedStorageItem) {
            $discountItems->push([
                'productListingId' => $raisedStorageItemKey,
                'qty' => $raisedStorageItem,
                'applyDiscount' => false
            ]);
        }
        return $discountItems;
    }

    public function productListingQualifies($productListing)
    {
        return false;
    }

}