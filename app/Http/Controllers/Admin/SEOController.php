<?php

namespace App\Http\Controllers\Admin;

use App\Video;
use App\Blog\Post;
use App\Http\Requests;
use App\Models\Course;
use App\AppEvents\Event;
use Illuminate\Http\Request;
use App\Presenters\Presenter;
use App\AppEvents\EventRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\SeoData;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\FaqQuestion;


class SEOController extends Controller
{

    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function index(Request $request,$name = null){
         $array = [
             'event' => New Event(),
             'presenters' => New Presenter(),
             'videos' => New Video(),
             'posts' => New Post(),
             'courses' => New Course(),
             'seodata' => New SeoData(),
             'faqQuestion' => New FaqQuestion()
            ];
        $model = $array['event']; 
        if($name == 'search')
        {
            return $this->search($request);
        }
        if($name == 'search_new')
        {
            return $this->search_new($request);
        }    
        if($name != null)
        {
            $model = $array[$name];
        }
        if($name == null)
        {
            $name ='event';
        }
        $events = $model->paginate(10);
        return view('admin.seo.index',compact('events','array','name'));
    }
    public function edit($id,$name){
        $tableName = $name;

        $array = [
            'event' => New Event(),
            'presenters' => New Presenter(),
            'videos' => New Video(),
            'posts' => New Post(),
            'courses' => New Course(),
            'faqQuestion' => New FaqQuestion()
           ];
           if($name != null)
           {
               $model = $array[$name];
           }
        $data = $model->where('id', $id)->first();
        return view('admin.seo.edit',compact('data','tableName','name'));
    }

    public function update(Request $request, $id, $name)
    {
        $array = [
            'event' => New Event(),
            'presenters' => New Presenter(),
            'videos' => New Video(),
            'posts' => New Post(),
            'courses' => New Course(),
            'faqQuestion' => New FaqQuestion()
           ];
           if($name != null)
           {
               $model = $array[$name];
           }
        $model
        ->where('id',$id)
        ->update([
            'keyword' => $request->keyword,
            'meta_description' => $request->meta_description,
        ]);
        
        alert()->success('Your SEO has been Updated.', 'Success');
        return redirect()->back();
    }
    public function search(Request $request)
    {
        $name = $request->tableName;
        $array = [
            'event' => New Event(),
            'presenters' => New Presenter(),
            'videos' => New Video(),
            'posts' => New Post(),
            'courses' => New Course(),
            'seodata' => New SeoData(),
            'faqQuestion' => New FaqQuestion()
           ];
       $model = $array['event'];  
       if($name != null)
       {
           $model = $array[$name];
       }
       $events = $model->search($request['name'], null, true)->paginate(10);
    //    dd($events);
       return view('admin.seo.index',compact('events','array','name'));
       
    }

    public function search_new(Request $request)
    {
         // Collect the new events
        $events = collect();
        $name = $request->tableName;
        $array = [
            'event' => New Event(),
            'presenters' => New Presenter(),
            'videos' => New Video(),
            'posts' => New Post(),
            'courses' => New Course(),
            'seodata' => New SeoData(),
            'faqQuestion' => New FaqQuestion()
           ];
           
        foreach($array as $a){
            $event = $a->where($a->getNameAttr(), 'like', "%".$request['name']."%")->get();
            // $events->push($event);
            $event->each(function ($e) use ($events) {
                            $events->push($e);

            });
        }
        $type = 'search_new';
        $page = (isset($_GET['page']))?$_GET['page']:1;
        $perPage = 10;
        $events = new \Illuminate\Pagination\LengthAwarePaginator(
            $events->forPage($page, $perPage), 
            $events->count(), 
            $perPage, 
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
       return view('admin.seo.index',compact('events','array','name','type'));
       
    }


    // public function search(Request $request)
    // {
    //     if($request->tableName == 'events'){
    //         $search = str_replace('--', '-', str_replace(' ', '-', strtolower(preg_replace('/[^a-zA-Z0-9 .]/', '', $request['name']))));
    //         $events = Event::where('name', 'LIKE', '%'.$search.'%')->get();
    //     }
    //     return view('admin.seo.list', compact('events'));
    // }
}
