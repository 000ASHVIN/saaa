<?php

namespace App\Store;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\Presenter;

class Listing extends Model
{
    protected $table = 'store_listings';
    protected $dates = ['created_at', 'updated_at', 'published_at'];
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['hasPhysicalProduct'];

    public static function search($search = false, $categoryId = false)
    {
        $results = collect([]);

        if ($categoryId && $categoryId != 0) {
            $listings = static::where('category_id', $categoryId)->get();
        } else
            $listings = static::all();

        $search = strtolower(str_replace('/[^A-z0-9 ]/g', '', trim($search)));
        if ($search && $search != "") {
            $searchFields = explode(" ", $search);
            foreach ($listings as $listing) {
                $comparisonTitle = strtolower(str_replace('/[^A-z0-9]/g', '', trim($listing->title)));
                if (str_contains($comparisonTitle, $searchFields)) {
                    if (!$results->has($listing->id))
                        $results->put($listing->id, $listing);
                }
            }
        } else
            return $listings;

        return $results;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            if ($listing->published_at === null)
                $listing->published_at = Carbon::now();
        });

        static::saving(function ($listing) {
            if ($listing->from_price === null || $listing->from_price == 0)
                $listing->from_price = $listing->priceOfCheapestProduct();
        });
    }

    /*
     * Scopes
     */

    /**
     * @param $query
     * @return mixed
     */
    public function activeScope($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function onSaleScope($query)
    {
        return $query->where('discount', '>', 0);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function featuredScope($query)
    {
        return $query->where('is_featured', true);
    }

    /*
     * Relationships
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @param $category
     * @return Model
     */
    public function setCategory($category)
    {
        return $this->category()->associate($category);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'store_listing_store_product')->withTimestamps();
    }

    /**
     * @param $products
     * @param array $pivots
     * @return array
     */
    public function addProducts($products, $pivots = [])
    {
        return $this->products()->saveMany($products, $pivots);
    }

    /**
     * @param Product $product
     * @param array $pivot
     * @return array
     */
    public function addProduct(Product $product, $pivot = [])
    {
        return $this->addProducts([$product], [$pivot]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productListings()
    {
        return $this->hasMany(ProductListing::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'store_discount_store_listing');
    }

    /**
     * @param $discount
     * @return Model
     */
    public function addDiscount($discount)
    {
        return $this->discounts()->save($discount);
    }

    /*
     * Aggregates
     */

    /**
     * @return double
     */
    public function priceOfCheapestProduct()
    {
        $products = $this->products()->get();
        if (!$products || sizeof($products) < 1)
            return 0;

        $lowestPrice = $products[0];
        foreach ($products as $product) {
            if ($product->price < $lowestPrice)
                $lowestPrice = $product->price;
        }
        return $lowestPrice;
    }

    /**
     * @return double
     */
    public function totalPriceOfAllProducts()
    {
        $total = 0;
        $products = $this->products()->get();
        foreach ($products as $product) {
            $total += $product->price;
        }
        return $total;
    }

    /**
     * @return int
     */
    public function earliestYearOfAllProducts()
    {
        $products = $this->products()->get();
        $lowestYear = $products[0]->year;
        foreach ($products as $product) {
            if ($product->year < $lowestYear)
                $lowestYear = $product->year;
        }

        return $lowestYear;
    }

    /**
     * @param null $products
     * @return int
     */
    public function totalCPDHours($products = null)
    {
        if (!$products)
            $products = $this->products()->get();

        $totalCPD = 0;
        foreach ($products as $product) {
            $totalCPD += $product->cpd_hours;
        }
        return $totalCPD;
    }

    public function upToCPD($products = null)
    {
        if ($products)
            $products = $this->products()->get();

        if (sizeof($products) === 1)
            return $products->first()->cpd_hours;

        $maxCPD = 0;
        foreach ($products as $product) {
            if ($product->cpd_hours > $maxCPD)
                $maxCPD = $product->cpd_hours;
        }

        return $maxCPD;
    }

    /**
     * @return bool
     */
    public function hasPhysicalProduct()
    {
        $products = $this->products()->get();
        $hasPhysicalProduct = false;
        foreach ($products as $product) {
            if ($product->is_physical) {
                $hasPhysicalProduct = true;
                break;
            }
        }
        return $hasPhysicalProduct;
    }

    public function getHasPhysicalProductAttribute()
    {
        return $this->hasPhysicalProduct();
    }

    public function presenters()
    {
        return $this->belongsToMany(Presenter::class, 'store_listing_presenter');
    }

    public function relatedListings()
    {
        return $this->belongsToMany(Listing::class, 'store_listing_related_listing');
    }
}
