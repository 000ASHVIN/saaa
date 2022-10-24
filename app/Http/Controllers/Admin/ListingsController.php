<?php

namespace App\Http\Controllers\Admin;

use App\Store\Category;
use App\Store\Listing;
use App\Store\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ListingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listings = Listing::with(['products'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.store.listings.index', compact('listings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all()->pluck('title', 'id');
        $products = Product::with(['listings'])->get();
        $selectedProducts = collect([]);
        return view('admin.store.listings.create', compact('categories', 'products', 'selectedProducts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\CreateUpdateListingRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateUpdateListingRequest $request)
    {
        $listing = Listing::create($request->only([
            'title',
            'category_id',
            'description',
            'image_url',
            'from_price',
            'discount_type',
            'discount'
        ]));

        if (count($request->get('products', [])) > 0) {
            $products = $request->products;
            $listing->products()->sync($products);
        }

        alert()->success('Your listing has been created', 'Success');
        return redirect()->route('admin.listings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all()->pluck('title', 'id');
        $listing = Listing::findOrFail($id);
        $products = Product::with(['listings'])->get();
        $selectedProducts = $listing->products->pluck('id')->toArray();
        return view('admin.store.listings.edit', compact('listing', 'categories', 'products', 'selectedProducts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Requests\CreateUpdateListingRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CreateUpdateListingRequest $request, $id)
    {
        $listing = Listing::findOrFail($id);
        $listing->update($request->only([
            'title',
            'category_id',
            'description',
            'image_url',
            'from_price',
            'discount_type',
            'discount',
            'is_active'
        ]));

        $products = $request->get('products', []);
        $listing->products()->sync($products);

        alert()->success('Your listing has been updated', 'Success');
        return redirect()->route('admin.listings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $listing = Listing::find($id);
        $listing->delete();
        alert()->success('Listing has been removed', 'Success');
        return back();
    }

    public function assignDiscount(Requests\AssignDiscountToListingRequest $request, $listingId)
    {
        $listing = Listing::findOrFail($listingId);
        $listing->discounts()->attach($request->discount_id);
        alert()->success('Discount has been added to listing', 'Success');
        return redirect()->route('admin.listings.edit', $listingId);
    }

    public function unassignDiscount($listingId, $discountId)
    {
        $listing = Listing::findOrFail($listingId);
        $listing->discounts()->detach($discountId);
        alert()->success('Discount has been removed from listing', 'Success');
        return redirect()->route('admin.listings.edit', $listingId);
    }
}
