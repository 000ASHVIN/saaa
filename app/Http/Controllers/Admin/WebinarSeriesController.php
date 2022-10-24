<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Video;
use App\Blog\Category;
use App\Subscriptions\Models\Feature;

class WebinarSeriesController  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request['name'];
        $webinar_series = Video::where('type','series');

        /* Search Webinar Series */
        if(isset($request['name']) && $request['name']!="")
        {
            $webinar_series->where('title', 'like', '%' . $search . '%');
        }

        $webinar_series = $webinar_series->orderBy('id', 'desc')->paginate(10);
        return view('admin.webinar_series.index', compact('webinar_series'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id','=','0')->get()->pluck('title', 'id');
        $features = Feature::all()->pluck('name','id');
        $videos = Video::where('type','single')->orderBy('id', 'desc')->get();
        return view('admin.webinar_series.create', compact('videos', 'categories', 'features'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $series_data = $request->only(['title','description','discount']);
        $series_data['type'] = 'series';
        $series_data['tag'] = 'studio';

        // Decide category
        $category_id = '';
        $newVideo = $request->all();
        if(!empty($newVideo['sub_sub_category'])) {
            $category_id = $newVideo['sub_sub_category'];
        }
        else if(!empty($newVideo['sub_category'])) {
            $category_id = $newVideo['sub_category'];
        }
        else if(!empty($newVideo['category'])) {
            $category_id = $newVideo['category'];
        }
        $series_data['category'] = $category_id;

        if($request->fix_price_series) {
            $series_data['fix_price_series'] = 1;
            $series_data['amount'] = $request->originalAmount?$request->originalAmount:0;
        }
        else {
            $series_data['fix_price_series'] = 0;
            $series_data['amount'] = null;
        }

        if(!isset($newVideo['VideoFeaturesList'])) {
            $newVideo['VideoFeaturesList'] = [];
        }

        $webinar_series = Video::create($series_data);
        
        $webinar_series->features()->sync($newVideo['VideoFeaturesList']);
        alert()->success('Webinar series added successfully.', 'Success');
        return redirect()->route('admin.webinar_series.edit', ['id' => $webinar_series->id]);
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
        $webinar_series = Video::find($id);
        $features = Feature::all()->pluck('name','id');
        $series_webinars = $webinar_series->webinars()->orderBy('series_videos.sequence','asc')->get();
        $videos = Video::where('type','single')->orderBy('title')->whereNotIn('id', $series_webinars->pluck('id'))->get();

        $allcategories = Category::all()->groupBy('parent_id');
        $categories = $allcategories[0]->pluck('title', 'id');
        $subcategories = [];
        $subsubcategories = [];

        // Find categories and sub categories
        $category_id='';
        $sub_category_id='';
        $sub_sub_category_id='';

        $category = $webinar_series->categories;
        $parentCategory = null;
        $mainCategory = null;
        if($category) {
            if($category->parentCategory()) {
                $parentCategory = $category->parentCategory();
                if($parentCategory->parentCategory()) {
                   $mainCategory =  $parentCategory->parentCategory();
                }
            }
        }

        if($category) {
            if($parentCategory) {
                if($mainCategory) {
                    $category_id = $mainCategory->id;
                    $sub_category_id = $parentCategory->id;
                    $sub_sub_category_id = $category->id;
                }
                else {
                    $category_id = $parentCategory->id;
                    $sub_category_id = $category->id;
                }
            }
            else {
                $category_id = $category->id;
            }
        }

        if($category_id && isset($allcategories[$category_id])) {
            $subcategories = $allcategories[$category_id]->pluck('title', 'id')->toArray();
        }
        if($sub_category_id && isset($allcategories[$sub_category_id])) {
            $subsubcategories = $allcategories[$sub_category_id]->pluck('title', 'id')->toArray();
        }

        return view('admin.webinar_series.edit', compact('videos', 'webinar_series', 'series_webinars', 'categories', 'subcategories', 'subsubcategories', 'category_id', 'sub_category_id', 'sub_sub_category_id', 'allcategories', 'features'));
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
        $webinar_series = Video::findOrFail($id);
        $series_data = $request->only(['title', 'discount', 'description']);

        // Decide category
        $category_id = '';
        $newVideo = $request->all();
        if(!empty($newVideo['sub_sub_category'])) {
            $category_id = $newVideo['sub_sub_category'];
        }
        else if(!empty($newVideo['sub_category'])) {
            $category_id = $newVideo['sub_category'];
        }
        else if(!empty($newVideo['category'])) {
            $category_id = $newVideo['category'];
        }
        $series_data['category'] = $category_id;

        if($request->fix_price_series) {
            $series_data['fix_price_series'] = 1;
            $series_data['amount'] = $request->originalAmount?$request->originalAmount:0;
        }
        else {
            $series_data['fix_price_series'] = 0;
            $series_data['amount'] = null;
        }

        if(!isset($newVideo['VideoFeaturesList'])) {
            $newVideo['VideoFeaturesList'] = [];
        }

        $webinar_series->update($series_data);
        $webinar_series->features()->sync($newVideo['VideoFeaturesList']);

        alert()->success('Webinar series updated successfully.', 'Success');
        return redirect()->route('admin.webinar_series.index');
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

    // Function to assign a webinar to series
    public function assignWebinar(Request $request, $series_id) {

        $webinar_series = Video::findOrFail($series_id);
        $webinar_id = $request->webinar_id;

        // Detach webinar if already exists
        $webinar_series->webinars()->detach($webinar_id);
        
        // Check last sequence
        $sequence = $webinar_series->webinar_sequence;

        $webinar_series->webinars()->attach(
            [ $webinar_id => ['sequence' => $sequence] ] 
        );
        return redirect()->back();

    }

    // Function which will set sequence for webinars
    public function setWebinarSequence(Request $request, $series_id) {

        $webinar_series = Video::findOrFail($series_id);
        $webinar_id = $request->webinar_id;

        $webinars = isset($request->webinars)?$request->webinars:[];
        $webinar_series->webinars()->sync(
            $webinars
        );

        return response()->json(['success'=>true]);

    }

}