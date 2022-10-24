<?php

namespace App\Repositories\StoreRepository\ListingRepository;

use App\Store\Listing;

class ListingRepository
{
    private $listing;
    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
    }

    public function findListing($id)
    {
        $listing = $this->listing->with([
            'productListings',
            'productListings.product',
            'productListings.product.tags',
            'productListings.listing',
            'presenters',
            'relatedListings',
            'discounts'
        ])->find($id);
        return $listing;
    }

    public function transformListing($listing)
    {
        $listing->productListings->transform(function ($productListing, $productListingKey) {
            $productListing->qualifyingGlobalDiscounts = $productListing->getQualifyingGlobalDiscounts();
            return $productListing;
        });
    }
}