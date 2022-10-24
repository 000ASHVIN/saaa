<?php

namespace App\Http\Controllers;

use App\Store\Category;
use App\Store\Listing;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StoreSearchController extends Controller
{
    public function search(Request $request, $categoryId = 0)
    {
        $search = $request->search;
        if (!$categoryId)
            $categoryId = $request->category;

        if (!$categoryId && !$search)
            return redirect()->route('store.index');

        $category = Category::find($categoryId);
        $resultsListings = Listing::search($search, $categoryId);
        $resultsListings = $resultsListings->keyBy('id');

        return view('store.search', ['search' => $search, 'category' => $category, 'resultsListings' => $resultsListings]);
    }
}
