<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ResourceCentreCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.resource_centre.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('admin.resource_centre.categories.show');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
