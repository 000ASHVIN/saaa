<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Event;
use App\Blog\Category;
use App\Video;
use App\VideoProvider;
use App\Presenters\Presenter;
use Illuminate\Http\Request;
use App\Subscriptions\Models\Feature;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use YAAP\Theme\Facades\Theme;
use App\Assessment;
use App\Assessments\Question;
use App\Assessments\Option;
use App\Link;
use Illuminate\Support\Str;
use App\Uuid;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Video::where('type','=','single')->orderBy('id', 'desc')->paginate(10);
        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id','=','0')->get()->pluck('title', 'id');
        $presenters = Presenter::orderBy('name')->get()->pluck('name','id');
        $features = Feature::all()->pluck('name','id');
        $videoProviders = VideoProvider::all();
        return view('admin.videos.create', compact('videoProviders', 'categories','features', 'presenters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\CreateUpdateVideoRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateUpdateVideoRequest $request)
    {
       $newVideo = $request->only(['title', 'hours', 'tag', 'amount', 'reference', 'video_provider_id', 'download_link', 'width', 'height', 'can_be_downloaded', 'view_link', 'category', 'sub_category', 'sub_sub_category', 'description','status','VideoFeaturesList', 'VideoPresentersList', 'view_resource']);
        $newVideo['can_be_downloaded'] = $newVideo['can_be_downloaded'] ? true : false;

         // Decide category
         $category_id = '';
         if(!empty($newVideo['sub_sub_category'])) {
             $category_id = $newVideo['sub_sub_category'];
         }
         else if(!empty($newVideo['sub_category'])) {
             $category_id = $newVideo['sub_category'];
         }
         else if(!empty($newVideo['category'])) {
             $category_id = $newVideo['category'];
         }
         
        $video = new Video([
            'title' => $newVideo['title'],
            'reference' => $newVideo['reference'],
            'video_provider_id' => $newVideo['video_provider_id'],
            'download_link' => $newVideo['download_link'],
            'width' => $newVideo['width'],
            'height' => $newVideo['height'],
            'can_be_downloaded' => $newVideo['can_be_downloaded'],
            'view_link' => $newVideo['view_link'],
            'category' => $category_id,
            'hours' => $newVideo['hours'],
            'tag' => $newVideo['tag'],
            'status' => $newVideo['status'],
            'amount' => $newVideo['amount'],
            'description' => $newVideo['description'],
            'view_resource' => $newVideo['view_resource'],
            'width' => '0',
            'height' => '0',
            'cover' => (env('APP_THEME') == 'taxfaculty' ? Theme::asset('/img/player.jpg') : "https://imageshack.com/a/img924/9673/A4DN1j.jpg")
        ]);

        $video->save();

        $video->features()->sync(! $newVideo['VideoFeaturesList'] ? [] : $newVideo['VideoFeaturesList']);
        $video->presenters()->sync(! $newVideo['VideoPresentersList'] ? [] : $newVideo['VideoPresentersList']);

        alert()->success('The video has been added.', 'Success');
        return redirect()->route('admin.videos.index');
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
        $edit = true;
        $allcategories = Category::all()->groupBy('parent_id');
        $categories = $allcategories[0]->pluck('title', 'id');
        $subcategories = [];
        $subsubcategories = [];
        $features = Feature::all()->pluck('name','id');
        
        $video = Video::findOrFail($id);
        $presenters = Presenter::orderBy('name')->get()->pluck('name','id');
        $videoProviders = VideoProvider::all();
        $events = Event::with(['venues'])->get();
        $assessments = Assessment::all();

        // Find categories and sub categories
        $category_id='';
        $sub_category_id='';
        $sub_sub_category_id='';

        $category = $video->categories;
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
        
        return view('admin.videos.edit', compact('video', 'videoProviders', 'events', 'categories', 'subcategories', 'subsubcategories', 'category_id', 'sub_category_id', 'sub_sub_category_id', 'allcategories','features', 'presenters', 'assessments', 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Requests\CreateUpdateVideoRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CreateUpdateVideoRequest $request, $id)
    {
        $video = Video::findOrFail($id);
        $newVideo = $request->only([
            'title', 'hours', 'tag', 'amount', 'reference', 'video_provider_id', 'download_link', 'width', 'height', 'can_be_downloaded', 'cover', 'view_link', 'category', 'sub_category', 'sub_sub_category', 'description','status'
,'VideoFeaturesList', 'VideoPresentersList', 'view_resource']);
        
        // Decide category
        $category_id = '';
        if(!empty($newVideo['sub_sub_category'])) {
            $category_id = $newVideo['sub_sub_category'];
        }
        else if(!empty($newVideo['sub_category'])) {
            $category_id = $newVideo['sub_category'];
        }
        else if(!empty($newVideo['category'])) {
            $category_id = $newVideo['category'];
        }
        
        $newVideo['can_be_downloaded'] = $newVideo['can_be_downloaded'] ? true : false;

        $video->update([
            'title' => $newVideo['title'],
            'reference' => $newVideo['reference'],
            'video_provider_id' => $newVideo['video_provider_id'],
            'download_link' => $newVideo['download_link'],
            'width' => $newVideo['width'],
            'height' => $newVideo['height'],
            'can_be_downloaded' => $newVideo['can_be_downloaded'],
            'view_link' => $newVideo['view_link'],
            'category' => $category_id,
            'hours' => $newVideo['hours'],
            'tag' => $newVideo['tag'],
            'amount' => $newVideo['amount'],
            'description' => $newVideo['description'],
            'view_resource' => $newVideo['view_resource'],
            'status' => $newVideo['status'],
            'width' => '0',
            'height' => '0',
            'cover' => (env('APP_THEME') == 'taxfaculty' ? Theme::asset('/img/player.jpg') : "https://imageshack.com/a/img924/9673/A4DN1j.jpg")
        ]);

      
        $video->features()->sync(! $newVideo['VideoFeaturesList'] ? [] : $newVideo['VideoFeaturesList']);
        $video->presenters()->sync(! $newVideo['VideoPresentersList'] ? [] : $newVideo['VideoPresentersList']);
        alert()->success('The video has been updated.', 'Success');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }

    public function getVideoProviderVideos($id)
    {
//        $videoProvider = VideoProvider::findOrFail($id);
//        return $videoProvider->instance->getVideos();
    }
    public function search(Request $request)
    { 
        $videos = New Video();
        if(isset($request['title']) && $request['title']!="")
        {
            $search = $request['title'];
            $videos = $videos->where('title', 'LIKE', '%'.$search.'%');
        }
        if(isset($request['tag']) && $request['tag']!="")
        {
            $videos = $videos->where('tag',$request['tag']);
        }
        $videos = $videos->paginate(10);
        return view('admin.videos.index', compact('videos'));
    }

    public function syncAssessments(Request $request, $video_id) {

        $video = Video::findOrFail($video_id);
        if($video) {
            $video->assessments()->sync($request->assessments?$request->assessments:[]);
            alert()->success('Your Assessment has been saved!', 'Success!');
            return back()->withInput(['tab' => 'assessment']);
        }
        alert()->error('Invalid request!', 'Error!');
        return back();
    }

    public function syncResourcesFromEvent($video_id) {
        
        $video = Video::findOrFail($video_id);
        if(count($video->links) || count($video->assessments)) {
            alert()->error('Links and assessments already copied from event!', 'Error!');
            return back();
        }
        else if(count($video->pricings)) {
            $event = $video->pricings->first()->event;
            if($event) {
                // $assessments = $event->assessments->pluck('id')->toArray();
                // $links = $event->links->pluck('id')->toArray();

                // Copy assessments
                $assessment_ids = [];
                $assessments = $event->assessments;
                foreach($assessments as $assessment) {

                    $copy_assessment = Assessment::create([
                        'guid' => Uuid::generate(),
                        'title' => $assessment->title,
                        'instructions' => $assessment->instructions,
                        'cpd_hours' => $assessment->cpd_hours,
                        'pass_percentage' => $assessment->pass_percentage,
                        'time_limit_minutes' => $assessment->time_limit_minutes,
                        'maximum_attempts' => $assessment->maximum_attempts,
                        'randomize_questions_order' => $assessment->randomize_questions_order
                    ]);

                    // Copy questions
                    foreach($assessment->questions as $question) {

                        $copy_question = $copy_assessment->questions()->create([
                            'guid' => Uuid::generate(),
                            'description' => $question->description,
                            'sort_order' => $question->sort_order
                        ]);

                        // Copy options
                        foreach($question->options as $option) {

                            $copy_option = $copy_question->options()->create([
                                'guid' => Uuid::generate(),
                                'description' => $option->description,
                                'symbol' => $option->symbol,
                                'is_correct' => $option->is_correct
                            ]);

                        }

                    }

                    $assessment_ids[] = $copy_assessment->id;

                }

                // Copy links
                $link_ids = [];
                $links = $event->links;
                foreach($links as $link) {
                    $copy_link = Link::create([
                        'name' => $link->name,
                        'url' => $link->url,
                        'instructions' => $link->instructions,
                        'secret' => $link->secret,
                    ]);
                    $link_ids[] = $copy_link->id;
                }
                
                $video->assessments()->sync($assessment_ids?$assessment_ids:[]);
                $video->links()->sync($link_ids?$link_ids:[]);

                alert()->success('Event resources copied successfully!', 'Success!');
                return back();
            }
        }
        alert()->error('No events linked!', 'Error!');
        return back();
    }
}