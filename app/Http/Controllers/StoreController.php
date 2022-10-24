<?php

namespace App\Http\Controllers;

use App\Store\Cart;
use App\Store\Listing;
use App\Store\Discount;
use Illuminate\Http\Request;
use App\Repositories\StoreRepository\ListingRepository\ListingRepository;

class StoreController extends Controller
{

    private $listingRepository;
    public function __construct(ListingRepository $listingRepository)
    {
        $this->listingRepository = $listingRepository;
    }

    public function index()
    {
        $listings = Listing::with(['products'])->where('is_active',1)->orderBy('title')->paginate(8);
        return view('store.index', compact('listings'));
    }

    public function show($id, Request $request)
    {
        $listing = $this->listingRepository ->findListing($id);
        if($listing && !$listing->is_active){
            alert()->error('No such data found', 'Error');
            return redirect('/');
        }
        $this->listingRepository->transformListing($listing);

        $cartItems = Cart::getAllStorageItems();
        $totalCartQty = Cart::getTotalQuantity();

        $discounts = $listing->discounts()->get();
        $globalDiscounts = Discount::globalDiscounts()->active()->get();
        $selectedProductId = $request->get('product', 0);

        return view('store.show', compact('listing', 'discounts', 'cartItems', 'totalCartQty', 'globalDiscounts', 'selectedProductId'));
    }
}