<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Blog\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UploadOrReplaceImage;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::paginate(10);
        $categorys = Category::where('parent_id',0)->get();
        
        return view('admin.blog.categories.index', compact('categories','categorys'));
    }

    public function create()
    {
        $categories = Category::get()->pluck('title','id')->toArray();

        return view('admin.blog.categories.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required']);
        $category = Category::create($request->only(['title', 'description','parent_id']));

        if($request->image){
            $image = UploadOrReplaceImage::UploadOrReplace('categories/category', 'image', $category,'full');
        }

        alert()->success('Your category has been created', 'Success!');
        return redirect()->route('admin.categories.index');
    }

    public function edit($slug)
    {
        $category = Category::where('slug', $slug)->first();
        $categories = Category::where('slug', '!=', $slug)
            ->get()
            ->pluck('title','id')
            ->toArray();
        return view('admin.blog.categories.edit', compact('category','categories'));
    }

    public function update(Request $request, $slug)
    {
        $this->validate($request, ['title' => 'required']);

        $category = Category::where('slug', $slug)->first();
        $category->slug = null;

        if($request->image){
            $image = UploadOrReplaceImage::UploadOrReplace('categories/category', 'image', $category,'full');
        }

        if($request->parent_id == $category->id) {
            alert()->error('Parent category can not be same.', 'Error!');
            return redirect()->back();
        }
        else {
            $category->update($request->only('title', 'description','parent_id'));
            if($request->remove_image) {
                $category->update(['image' => '']);
            }
            alert()->success('Your category has been updated', 'Success!');
            return redirect()->route('admin.categories.index');
        }
    }

    public function destroy(Request $request)
    {
        $this->validate($request, ['hdn_category_id' => 'required']);
        $categoryId = $request->input('hdn_category_id');
        $category = Category::find($categoryId);
        $category->delete();

        alert()->success('Your category has been deleted', 'Success!');
        return back();
    }
}
