<?php

namespace App\Http\Controllers\Admin;

use App\Assessment;
use App\Store\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['orders'])->orderBy('id','desc')->paginate(10);
        return view('admin.store.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.store.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\CreateUpdateProductRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateUpdateProductRequest $request)
    {
        $product = $request->only([
            'topic',
            'year',
            'title',
            'price',
            'is_physical',
            'cpd_hours',
            'stock',
            'allow_out_of_stock_order'
        ]);

        $product['is_physical'] = $product['is_physical'] ? true : false;
        $product['allow_out_of_stock_order'] = $product['allow_out_of_stock_order'] ? true : false;
        $product = Product::create($product);

        alert()->success('Your product has been created.', 'Success');
        return redirect()->route('admin.products.edit', $product->id);
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
        $assessments = Assessment::all();
        $product = Product::with('listings', 'assessments')->findOrFail($id);
        return view('admin.store.products.edit', compact('product', 'assessments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Requests\CreateUpdateProductRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CreateUpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $updated = $request->only([
            'topic',
            'year',
            'title',
            'price',
            'is_physical',
            'cpd_hours',
            'stock',
            'allow_out_of_stock_order'
        ]);
        $updated['is_physical'] = $updated['is_physical'] ? true : false;
        $product['allow_out_of_stock_order'] = $product['allow_out_of_stock_order'] ? true : false;
        $product->update($updated);

        alert()->success('Your product has been updated.', 'Success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        alert()->success('Product has been removed', 'Success');
        return back();
    }

    public function assignListing(Requests\AssignProductToListingRequest $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $product->listings()->attach($request->listing_id);
        alert()->success('Product has been added to listing', 'Success');
        return redirect()->route('admin.products.edit', $productId);
    }

    public function unassignListing($productId, $listingId)
    {
        $product = Product::findOrFail($productId);
        $product->listings()->detach($listingId);
        alert()->success('Product has been removed from listing', 'Success');
        return redirect()->route('admin.products.edit', $productId);
    }
}
