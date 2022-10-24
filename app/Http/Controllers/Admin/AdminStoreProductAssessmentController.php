<?php

namespace App\Http\Controllers\Admin;

use App\Store\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminStoreProductAssessmentController extends Controller
{
    public function store(Request $request, $product)
    {
        $product = Product::find($product);
        $product->assessments()->sync(! $request->assessment ? [] : $request->assessment);
        alert()->success('Your assessment has been linked!', 'Success!');
        return back();
    }
}
