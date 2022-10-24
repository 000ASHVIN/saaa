<?php

namespace App\Http\Controllers\Admin;

use App\Store\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('listings')->paginate(10);
        return view('admin.store.categories.index', compact('categories'));
    }


    public function create()
    {
        return view('admin.store.categories.create');
    }


    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required']);
        Category::create($request->except('_token'));
        alert()->success('Your category has been created', 'Success!');
        return back();
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.store.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $category->update($request->except('_token'));
        alert()->success('Your category has been updated', 'Success!');
        return back();
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        alert()->success('Your category has been deleted', 'Success!');
        return back();
    }
}
