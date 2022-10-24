<?php

namespace App\Store\Cart;

use App\AppEvents\Event;
use App\Store\Discount;
use App\Store\ProductListing;
use App\Video;

class CartProductListing
{
    public $id;
    public $title;
    public $detailedTitle;
    public $qty;
    public $price;
    public $CPDHours;
    public $isPhysical;
    public $discountedPrice;
    public $discountAmount;
    public $discountPercentage;
    public $hasDiscount;
    public $hasGlobalDiscount;
    public $listingUrl;
    public $productListing;
    public $product;
    public $listing;
    public $globalDiscounts;
    public $storageItem;
    public $model;

    public function __construct($storageItem, $qty = 1, $globalDiscounts = [])
    {
        $this->model = explode("\\",$storageItem->model);
        $this->model = end($this->model);
        if($storageItem->model =="App\Store\ProductListing"){
            $this->storageItem = $storageItem;
            $this->id = $storageItem->id;
            $this->productListing = ProductListing::with(['product', 'listing'])->findOrFail($this->id);
            $this->product = $this->productListing->product;
            $this->listing = $this->productListing->listing;
            $this->qty = $qty;
            $this->price = floatval($this->productListing->product->price);
            $this->CPDHours = $this->productListing->product->cpd_hours;
            $this->isPhysical = $this->productListing->product->is_physical;
            $this->discountedPrice = $this->price;
            $this->listingUrl = route('store.show', $this->productListing->listing->id);
            $this->hasDiscount = false;
            if (!is_array($globalDiscounts))
                $this->globalDiscounts = $globalDiscounts;
            else
                $this->globalDiscounts = collect($globalDiscounts);
            $this->hasGlobalDiscount = false;
         
            if ($this->globalDiscounts && count($this->globalDiscounts) > 0)
                $this->hasGlobalDiscount = true;
    
            $this->compileAndCompute();
        }elseif($storageItem->model == 'App\Video' || $storageItem->model == 'App\AppEvents\Event'){
            $this->storageItem = $storageItem;
            $this->id = $storageItem->id;
            if($storageItem->model == 'App\Video'){
                $this->productListing =Video::findOrFail($this->id);
                $this->product = $this->productListing;
                $this->listingUrl = route('webinars_on_demand.show', $this->product->slug);
                $this->price = floatval($this->product->amount);
            }elseif($storageItem->model == 'App\AppEvents\Event'){
                $Eventid = explode("-",$storageItem->id);
                if(count($Eventid)>0)
                {
                    $this->id = $Eventid[1];
                }
                
                $this->productListing =Event::findOrFail($this->id);
                $this->product = $this->productListing;
                $this->venue = $storageItem->venue;
                $this->venuePlace = $this->productListing->venues()->where('id',$this->venue)->first();
                $this->price = ($this->venuePlace->pricings()->where('id',$storageItem->pricing)->first())?floatval($this->venuePlace->pricings()->where('id',$storageItem->pricing)->first()->price):floatval($this->product->amount);
                $this->listingUrl = route('events.show', $this->product->slug);
            }
            
            $this->listing = [];
            $this->qty = $qty;
            
            $this->CPDHours = $this->product->hours;
            $this->isPhysical = 0;
            $this->discountedPrice = $this->price;
            
            $this->hasDiscount = false;
            if (!is_array($globalDiscounts))
                $this->globalDiscounts = $globalDiscounts;
            else
                $this->globalDiscounts = collect($globalDiscounts);
            $this->hasGlobalDiscount = false;
         
            if ($this->globalDiscounts && count($this->globalDiscounts) > 0)
                $this->hasGlobalDiscount = true;
    
            //$this->compileAndCompute();
        }
       
    }

    public function compileAndCompute()
    {
        $this->compileTitle();
        $this->calculateDiscountedPrice();
        $this->calculateDiscountPercentage();
        $this->compileDetailedTitle();
    }

    public function incrementQty($number = 1)
    {
        $this->qty += $number;
    }

    public function toInvoiceItemArray()
    {
        if($this->model == "ProductListing"){
            return [
                'type' => 'product',
                'name' => $this->title,
                'description' => $this->productListing->listing->category->title,
                'quantity' => $this->qty,
                'price' => $this->price,
                'discount' => $this->discountAmount,                
                'item_type'=> ($this->storageItem)?$this->storageItem->model: null,
                'item_id'=> ($this->storageItem)?$this->storageItem->id: null
            ];
        }elseif($this->model == 'Video' || $this->model == 'Event' || $this->model == 'Event'){
            return [
                'type' => 'product',
                'name' => $this->title,
                'description' => "Webinar On-Demand",
                'quantity' => $this->qty,
                'price' => $this->price,
                'discount' => $this->discountAmount,
                'item_type'=> ($this->storageItem)?$this->storageItem->model: null,
                'item_id'=> ($this->storageItem)?$this->storageItem->id: null
            ];
        }
    }

    private function compileTitle()
    {
        if($this->model == "ProductListing"){
            $this->title = $this->productListing->product->title;
            if ($this->productListing->product->topic && $this->productListing->product->topic != "")
                $this->title = $this->productListing->product->topic . ' - ' . $this->title;
            else
                $this->title = $this->productListing->listing->title . ' - ' . $this->title;
        }elseif($this->model == 'Video' || $this->model == 'Event'){
            $this->title = $this->product->title;
        }
    }

    private function compileDetailedTitle()
    {
        if($this->model == "ProductListing"){
        $this->detailedTitle = $this->title;
        if ($this->productListing->product->year)
            $this->detailedTitle = $this->detailedTitle . ' (' . $this->productListing->product->year . ')';
        if ($this->productListing->discount > 0) {
            $this->detailedTitle = $this->detailedTitle . ' [-' . $this->discountPercentage . '%]';
        }
        }elseif($this->model == 'Video' || $this->model == 'Event'){
            $this->detailedTitle = $this->product->title;
            if(isset($this->venue))
            {
                if($this->venuePlace){
                    $this->detailedTitle = $this->detailedTitle . ' (Venue :' . $this->venuePlace->name . ' City : '.$this->venuePlace->city.')';
                }
            }
        }
    }

    private function calculateDiscountedPrice()
    {
        $this->calculateDiscounts();
        $this->discountedPrice = $this->price - $this->discountAmount;
    }

    private function calculateDiscounts()
    {
        $this->discountAmount = 0;
        $this->discountedPrice = $this->price;
        $this->calculateProductDiscount();
        $this->calculateListingDiscount();
        if ($this->hasGlobalDiscount)
            $this->calculateGlobalDiscount();
        if ($this->price != $this->discountedPrice)
            $this->hasDiscount = true;
    }

    private function calculateProductDiscount()
    {
        if($this->model =="App\Store\ProductListing"){
            $discount = calculateDiscount($this->discountedPrice, $this->productListing->discount, $this->productListing->discount_type);
            $this->discountAmount += $discount;
            $this->discountedPrice -= $discount;
        }
    }

    private function calculateListingDiscount()
    {
        if($this->model =="App\Store\ProductListing"){
            $discount = calculateDiscount($this->discountedPrice, $this->productListing->listing->discount, $this->productListing->listing->discount_type);
            $this->discountAmount += $discount;
            $this->discountedPrice -= $discount;
        }
    }


    private function calculateGlobalDiscount()
    {
        $globalDiscounts = $this->globalDiscounts->sortBy('id');
        foreach ($globalDiscounts as $globalDiscount) {
            $discount = calculateDiscount($this->discountedPrice, $globalDiscount->value, $globalDiscount->type);
            $this->discountAmount += $discount;
            $this->discountedPrice -= $discount;
        }
    }

    private function calculateDiscountPercentage()
    {
        if ($this->price > 0 ){
            $this->discountPercentage = round(($this->discountAmount / $this->price) * 100.00, 2);
        }
    }
}