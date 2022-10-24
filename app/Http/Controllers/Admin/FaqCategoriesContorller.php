<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\FaqCategories;
use App\Blog\Category;

class FaqCategoriesContorller extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function link()
    {
        $faqCategories = FaqCategories::all()->pluck('name', 'id')->toArray();
        $categories = Category::where('parent_id','0')->get()->pluck('title', 'id')->toArray();
        return view('admin.faq_category.create_category_link', compact('faqCategories', 'categories'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function link_store(Request $request)
    {
        $this->validate($request, [
            'category' => 'required', 
            'faq_category_type' => 'required'
        ]);

        $category_id = $request->category;
        $category = Category::findOrFail($category_id);
        $category->fill(['faq_category_id'=>$request->faq_category_type]);
        $category->save();

        // Add type to all sub categories
        $faqCategory = FaqCategories::find($request->faq_category_type);
        $subcategories = Category::where('parent_id', $category_id)->get();
        $topics = Category::whereIn('parent_id', $subcategories->pluck('id'))->get();
        
        $categories = collect();
        $categories->push($category_id);
        foreach($subcategories as $subcategory) {
            $categories->push($subcategory->id);
        }
        foreach($topics as $topic) {
            $categories->push($topic->id);
        }
        Category::whereIn('id',$categories)->update(['faq_type'=>$faqCategory->type]);

        alert()->success('Category linked successfully!', 'Success');
        return redirect()->route('faq.categories');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unlink(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->fill(['faq_category_id'=>'0']);
        $category->save();

        // Remove type from all sub categories
        $subcategories = Category::where('parent_id', $id)->get();
        $topics = Category::whereIn('parent_id', $subcategories->pluck('id'))->get();
        
        $categories = collect();
        $categories->push($id);
        foreach($subcategories as $subcategory) {
            $categories->push($subcategory->id);
        }
        foreach($topics as $topic) {
            $categories->push($topic->id);
        }
        Category::whereIn('id',$categories)->update(['faq_type'=>'']);

        alert()->success('Category unlinked successfully!', 'Success');
        return back();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
