<?php

namespace App\Store;

use App\Interfaces\Cartable;
use App\Interfaces\Invoiceable;
use App\Traits\IsCartable;
use App\Traits\IsInvoiceable;
use Illuminate\Database\Eloquent\Model;

class ProductListing extends Model
{
    protected $table = 'store_listing_store_product';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = ['id', 'listing_id', 'product_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function addToCart()
    {
        Cart::addProductListing($this);
    }

    public function getQualifyingGlobalDiscounts()
    {
        $qualifyingGlobalDiscounts = collect([]);
        $globalDiscounts = Discount::active()->globalDiscounts()->get();
        $globalDiscounts = $globalDiscounts->keyBy('id');
        $listingGlobalDiscounts = $this->listing->discounts;
        if ($listingGlobalDiscounts && count($listingGlobalDiscounts) > 0)
            $globalDiscounts->merge($listingGlobalDiscounts->keyBy('id'));

        foreach ($globalDiscounts as $globalDiscount) {
            if ($globalDiscount->instance->productListingQualifies($this))
                $qualifyingGlobalDiscounts->put($globalDiscount->id, $globalDiscount);
        }

        return $qualifyingGlobalDiscounts;
    }
}
