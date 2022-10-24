<?php

namespace App\Http\Controllers;

use App\AppEvents\Event;
use App\Http\Requests\AddStoreProductToCartRequest;
use App\Repositories\StoreRepository\ListingRepository\ListingRepository;
use App\Store\Cart;
use App\Store\Listing;
use App\Store\ProductListing;
use App\Http\Requests;
use App\Video;

class StoreCartController extends Controller
{

    private $listingRepository;
    public function __construct(ListingRepository $listingRepository)
    {
        $this->listingRepository = $listingRepository;
    }

    public function addToCart(AddStoreProductToCartRequest $request, $listing_id)
    {
        $productListingSelectedOption = $request->product_listing_selected_option;
        $productListingId = $request->product_listing_id;

        if (!$productListingSelectedOption || (is_numeric($productListingSelectedOption) && $productListingSelectedOption == 0)) {
            alert()->error('Please select a product to add to cart', 'Error');
            return redirect()->route('store.show', $listing_id);
        }

        if ($productListingSelectedOption == 'all') {
            $productListings = Listing::findOrFail($listing_id)->productListings()->get();
            foreach ($productListings as $productListing) {
                $productListing->addToCart();
            }

            alert()->success(count($productListings) . ' items added to cart.', 'Success');
            return redirect()->route('store.show', $listing_id);
        }

        $productListing = ProductListing::findOrFail($productListingId);
        $listing = $this->listingRepository->findListing($listing_id);
        $productListing->addToCart();

        alert()->success($listing->title . ': ' . $productListing->product->title . ' added to cart.', 'Success');
        return redirect()->route('store.show', $listing_id);
    }

    public function cart()
    {
        $cartItems = Cart::getAllCartProductListings()->sortBy('discountPercentage');
        if (!$cartItems || count($cartItems) < 1) {
            return redirect()->route('store.index');
        }

        $cartTotalPrice = Cart::getTotalPrice();
        $cartTotalDiscount = Cart::getTotalDiscount();
        $cartTotalDiscountedPrice = Cart::getTotalDiscountedPrice();

        return view('store.cart', compact('cartItems', 'cartTotalPrice', 'cartTotalDiscount', 'cartTotalDiscountedPrice'));
    }

    public function removeFromCart($productListingId, $qty,$model)
    {
        if($model == "ProductListing"){
            $productListing = ProductListing::findOrFail($productListingId);
        }elseif($model == "Video"){
            $productListing = Video::findOrFail($productListingId);
            $productListing->listing = $productListing;
            $productListing->product = $productListing;
        }elseif($model == "Event"){
            $product = explode("-",$productListingId);
            if(count($product)>0)
            {
                $product = $product[1];
            }
            $productListing = Event::findOrFail($product);
            $productListing->listing = $productListing;
            $productListing->product = $productListing;
        }
        Cart::removeProductListingWithQty($productListingId, $qty);
        $this->checkCartQuantity($qty, $productListing);

        if (Cart::getTotalQuantity() > 0)
            return redirect()->route('store.cart');
        return redirect()->route('webinars_on_demand.home');
    }

    public function updateCart($productListingId, $qty)
    {
        $productListing = ProductListing::findOrFail($productListingId);
        Cart::updateProductListingQty($productListingId, $qty);

        alert()->success($productListing->listing->title . ' - ' . $productListing->product->title . ' quantity has been updated.', 'Success');
        if (Cart::getTotalQuantity() > 0)
            return redirect()->route('store.cart');
        return redirect()->route('store.index');
    }

    public function clearCart()
    {
        Cart::clear();
        alert()->success('All items have been removed from your cart.', 'Success');
        return redirect()->route('webinars_on_demand.home');
    }

    public function checkCartQuantity($qty, $productListing)
    {
        if ($qty > 1)
            alert()->success($productListing->listing->title . ' - ' . $productListing->product->title . ' x ' . $qty . ' have been removed from your cart.', 'Success');
        else
            alert()->success($productListing->listing->title . ' - ' . $productListing->product->title . ' has been removed from your cart.', 'Success');
    }
}
