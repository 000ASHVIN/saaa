<?php

namespace App\Http\Controllers\ResourceCentre;
use App\ActList;
use App\AppEvents\Event;
use App\Blog\Post;
use App\FaqQuestion;
use App\Store\Product;
use App\SupportTicket;
use App\Thread;
use App\Video;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\ActivityLog;
use App\Billing\Item;
use App\Blog\Category;
use App\FaqCategories;
use App\Users\User;
use Carbon\Carbon;
use DB;
use stdClass;

class FrontendResourceCentreController extends Controller
{
    /*
     * Home Page
     */
    public function index()
    {
        return view('resource_centre.index');
    }

    /*
     * Search Page Results
     */
    public function search(Request $request)
    {
        $search = collect();
        // dd(User::where('id',5297)->first());
        $search_category = collect();
        $title = $request['search'];
        $validator = \Validator::make($request->all(), ['search' => 'required']);
        if ($validator->fails()) {
            alert()->error('The search field is required when searching..', 'Error');
            return redirect()->route('resource_centre.home');
        }

        /*
         * Posts
         */
        $articles = Post::with('categories');
        $articles = $articles->search($request['search'], null, true)
                    ->orderByRaw('relevance desc,id desc')->get();

        // $articles = $articles->search($request['search'], null, true)->orderByRaw('relevance desc,id desc')->toSql();
        //     dd($articles);

        foreach ($articles as $article) {
            // dd($article);
            $article->search_type = 'articles';
            // $slug = '';
            // foreach($article->categories as $category) {
            //     $slug = $slug ." ". $category->slug;
            // }
            // $article->category = $slug;
            // $article->search_date = $article->created_at->format('Y-m-d');
            $article->created = $article->created_at->format('d F Y');
            $search->push($article);
        }

        $response = $this->getArticleCategories($articles);
        $article_categories = $response[0];
        $article_subcategories = $response[1];

        // dd($articles);
        /*
         * FAQ
         */
        $faqs = FaqQuestion::with('categories', 'tags');
        $faqs = $faqs->search($request['search'], null, true)->orderByRaw('relevance desc,id desc')->whereHas('tags', function($query){
                    $query->where('type', 'technical');
                })
                ->get();

        foreach ($faqs as $faq) {
            $faq->search_type = 'faqs';
            // $slug = '';
            // foreach($faq->categories as $category) {
            //     $slug = $slug ." ". $category->slug;
            // }
            // $faq->category = $slug;
            // $faq->search_date = $faq->created_at->format('Y-m-d');
            $faq->created = $faq->created_at->format('d F Y');
            $search->push($faq);
        }
        $response = $this->getFaqCategories($faqs);
        $faq_categories = $response[0];
        $faq_subcategories = $response[1];

        
        /*
         * Webinars on Demand
         */
        $webinars = Video::with('categories');
        $webinars = $webinars->search($request['search'], null, true)->where('tag', 'studio')->where('status', 0)->orderByRaw('relevance desc,id desc')->get();

        foreach ($webinars as $webinar) {
            $webinar->search_type = 'webinars';
            // if($webinar->categories){
            //     $webinar->category = $webinar->categories->slug;
            // }
            // $webinar->search_date = $webinar->created_at->format('Y-m-d');
            $webinar->created = $webinar->created_at->format('d F Y');
            $search->push($webinar);
        }
        
        $response = $this->getWebinarCategories($webinars);
        $webinar_categories = $response[0];
        $webinar_subcategories = $response[1];
        
        /*
         * Events
         */
        $events = Event::with('categories');
        $events = $events->search($request['search'], null, true)->has('pricings')->where('is_active', true)->orderByRaw('relevance DESC,events.start_date desc')->get();
        foreach ($events as $event) {
            $event->search_type = 'events';
            // $slug = '';
            // foreach($event->categories as $category) {
            //     $slug = $slug ." ". $category->slug;
            // }
            // $event->category = $slug;
            // $event->search_date = $event->start_date->format('Y-m-d');
            $event->created = $event->created_at->format('d F Y');
            $search->push($event);
        }
        $response = $this->getEventCategories($events);
        $event_categories = $response[0];
        $event_subcategories = $response[1];
        // dd($event_categories);
        /*
        * Acts
        */
        $acts = ActList::whereRaw(1);
        $acts = $acts->search($request['search'], null, true)
                ->orderByRaw('relevance DESC,id desc')->get();
        foreach($acts as $act) {
            $act->search_type = 'acts';
            // $act->search_date = $act->created_at->format('Y-m-d');
            $act->created = $act->created_at->format('d F Y');
            $search->push($act);
        }

        /*
         * Courses
         */
        $courses = Course::with('categories')->where('display_in_front','1');
        $courses = $courses->search($request['search'], null, true)->where('is_publish', true)->orderByRaw('relevance DESC,courses.start_date desc')->get();
        foreach($courses as $course) {
            $course->search_type = 'courses';
            // $slug = '';
            // foreach($course->categories as $category) {
            //     $slug = $slug ." ". $category->slug;
            // }
            // $course->category = $slug;
            // $course->search_date = $course->start_date->format('Y-m-d');
            $course->created = $course->created_at->format('d F Y');
            $search->push($course);
        }

        $response = $this->getCourseCategories($courses);
        $course_categories = $response[0];
        $course_subcategories = $response[1];
        
        /*
         * Threads
         */
        $tickets = SupportTicket::with('thread');
        $tickets = $tickets->search($request['search'], null, true)->orderByRaw('relevance desc,id desc')->has('thread')->get()->filter(function ($ticket) {
            if ($ticket->thread->open_to_public == true) {
                return $ticket;
            }
        });
        foreach ($tickets as $ticket) {
            $ticket->search_type = 'tickets';
            // $ticket->search_date = $ticket->created_at->format('Y-m-d');
            $ticket->created = $ticket->created_at->format('d F Y');
            $search->push($ticket);
        }

        // dd($search->first());
        // Sort
        $allRecords = collect();
        $search = $search->sortByDesc('relevance');
        $search = $search->groupBy('relevance');

        foreach ($search as $group) {
            $group = $group->sortByDesc('created_at');
            foreach ($group as $item) {

                if($item->description){
                    unset($item->description);
                }
                if($item->answer){
                    unset($item->answer);
                }
                if(!$item->short_description){
                    $item->short_description = str_replace('-', ' ', $item->slug);
                }

                $allRecords->push($item);
            }
        }
        // dd($allRecords);
        $categories = [
            [
                'category' => $webinar_categories->toArray(),
                'sub_category' => $webinar_subcategories->toArray(),
                'type' => 'webinars'
            ],
            [
                'category' => $faq_categories->toArray(),
                'sub_category' => $faq_subcategories->toArray(),
                'type' => 'faqs'
            ],
            [
                'category' => $article_categories->toArray(),
                'sub_category' => $article_subcategories->toArray(),
                'type' => 'articles'
            ],
            [
                'category' => $event_categories->toArray(),
                'sub_category' => $event_subcategories->toArray(),
                'type' => 'events'
            ],
            [
                'category' => $course_categories->toArray(),
                'sub_category' => $course_subcategories->toArray(),
                'type' => 'courses'
            ]
        ];
        // dd($categories[4]);
        $categories_json = json_encode($categories);
        // $allRecords->each(function($record){
        //     if($record->description){
        //         unset($record->description);
        //     }
        //     if($record->answer){
        //         unset($record->answer);
        //     }
        //     if(!$record->short_description){
        //         $record->short_description = str_replace('-', ' ', $record->slug);
        //     }
        // });
        // dd($allRecords->first());
        
        return view('resource_centre.search_results', compact('title', 'articles', 'faqs', 'events', 'webinars', 'tickets', 'acts', 'allRecords', 'courses', 'categories', 'categories_json'));
    }

    public function addParentCategoryIfNot($category) {
        $parent_category = new stdClass();
        $cat = Category::find($category->parent_category_id);
        // dd($cat);
            $parent_category->category_id = $cat->id;
            $parent_category->parent_category_id = 0;
            $parent_category->count = 0;
            $parent_category->slug = $cat->slug;
            $parent_category->category_title = $cat->title;
        return $parent_category;
        
    }

    public function showSubCategoriesWithParentCategories($categories, $sub_categories) {
        $categories_ids = $categories->pluck('category_id')->toArray();
        foreach($sub_categories as $sub_category){
            // check if sub category's parent category is not available in parent array.
            if(!in_array($sub_category->parent_category_id, $categories_ids)) {
                $parent_category = $this->addParentCategoryIfNot($sub_category);
                $categories->push($parent_category);
                $categories_ids[] = $sub_category->parent_category_id;
            }

            foreach($categories as $category) {
                if($category->category_id == $sub_category->parent_category_id) {
                    $category->count = (int)$category->count + (int)$sub_category->count;
                    if(isset($category->child)) {
                        $category->child = $category->child .",". $sub_category->slug;
                    } else {
                        $category->child = $sub_category->slug;
                    }
                }
            }
        }
        return [$categories, $sub_categories];
    }

    public function getArticleCategories($articles, $combined = false) {
        $article_categories = Category::select('categories.id as category_id', 'categories.parent_id as parent_category_id','categories.id',DB::raw("count(category_post.category_id) as count"),'categories.title as category_title', 'categories.slug')
            ->join('category_post','category_post.category_id', '=','categories.id')
            ->where('parent_id' , '=','0')
            ->whereIn('category_post.post_id', $articles->pluck('id')->toArray())
            ->groupBy('categories.id')
            ->get();

        $article_subcategories = Category::select('categories.id as category_id', 'categories.parent_id as parent_category_id','categories.id',DB::raw("count(category_post.category_id) as count"),'categories.title as category_title', 'categories.slug')
            ->join('category_post','category_post.category_id', '=','categories.id')
            ->where('parent_id' , '!=','0')
            ->whereIn('category_post.post_id', $articles->pluck('id')->toArray())
            ->groupBy('categories.id')
            ->get();
        if($combined) {
            return array_merge($article_categories->toArray(), $article_subcategories->toArray());
        }
        return $this->showSubCategoriesWithParentCategories($article_categories, $article_subcategories);
    }

   public function getFaqCategories($faqs, $combined = false) {
        $faq_categories = Category::select('categories.id as category_id', 'categories.parent_id as parent_category_id','categories.title as category_title', 'categories.slug', DB::raw("count(categories.id) as count"))
                ->join('category_faq_question', 'category_faq_question.category_id', '=', 'categories.id')
                ->where('categories.faq_type', 'technical')
                ->where('parent_id' , '=','0')
                ->whereIn('category_faq_question.faq_question_id', $faqs->pluck('id')->toArray())
                ->groupBy('categories.id')
                ->get();

        $faq_subcategories = Category::select('categories.id as category_id', 'categories.parent_id as parent_category_id','categories.title as category_title', 'categories.slug', DB::raw("count(categories.id) as count"))
                ->join('category_faq_question', 'category_faq_question.category_id', '=', 'categories.id')
                ->where('categories.faq_type', 'technical')
                ->where('parent_id' , '!=','0')
                ->whereIn('category_faq_question.faq_question_id', $faqs->pluck('id')->toArray())
                ->groupBy('categories.id')
                ->get();
        if($combined) {
            return array_merge($faq_categories->toArray(), $faq_subcategories->toArray());
        }

        return $this->showSubCategoriesWithParentCategories($faq_categories, $faq_subcategories);
   }

   public function getWebinarCategories($webinars) {

        $webinar_categories = Video::select('categories.id as category_id', 'categories.parent_id as parent_category_id', 'categories.title as category_title', 'categories.slug', DB::raw("count(videos.category) as count"))
            ->join('categories', 'categories.id', '=', 'videos.category')
            ->where('parent_id' , '=','0')
            ->whereIn('videos.id', $webinars->pluck('id')->toArray())
            ->whereNotNull('videos.category')
            ->groupBy('videos.category')
            ->get();

        $webinar_subcategories = Video::select('categories.id as category_id', 'categories.parent_id as parent_category_id', 'categories.title as category_title', 'categories.slug', DB::raw("count(videos.category) as count"))
            ->join('categories', 'categories.id', '=', 'videos.category')
            ->where('parent_id' , '!=','0')
            ->whereIn('videos.id', $webinars->pluck('id')->toArray())
            ->whereNotNull('videos.category')
            ->groupBy('videos.category')
            ->get();
        
        return $this->showSubCategoriesWithParentCategories($webinar_categories, $webinar_subcategories);
   }

   public function getEventCategories($events, $combined = false) {
        $event_categories = Category::select('categories.id as category_id', 'categories.parent_id as parent_category_id', 'categories.title as category_title', 'categories.slug', DB::raw("count(categories.id) as count"))
            ->join('category_event', 'category_event.category_id', '=', 'categories.id')
            ->where('parent_id' , '=','0')
            ->whereIn('category_event.event_id', $events->pluck('id')->toArray())
            ->whereNotNull('categories.id')
            ->groupBy('categories.id')
            ->get();

        $event_subcategories = Category::select('categories.id as category_id', 'categories.parent_id as parent_category_id', 'categories.title as category_title', 'categories.slug', DB::raw("count(categories.id) as count"))
            ->join('category_event', 'category_event.category_id', '=', 'categories.id')
            ->where('parent_id' , '!=','0')
            ->whereIn('category_event.event_id', $events->pluck('id')->toArray())
            ->whereNotNull('categories.id')
            ->groupBy('categories.id')
            ->get();
        
        if($combined) {
            return array_merge($event_categories->toArray(), $event_subcategories->toArray());
        }
        return $this->showSubCategoriesWithParentCategories($event_categories, $event_subcategories);
   }

   public function getCourseCategories($courses, $combined = false) {
        $course_categories = Category::select('categories.id as category_id', 'categories.parent_id as parent_category_id', 'categories.title as category_title', 'categories.slug', DB::raw("count(categories.id) as count"))
            ->join('category_course', 'category_course.category_id', '=', 'categories.id')
            ->where('parent_id' , '=','0')
            ->whereIn('category_course.course_id', $courses->pluck('id')->toArray())
            ->whereNotNull('categories.id')
            ->groupBy('categories.id')
            ->get();

        $course_subcategories = Category::select('categories.id as category_id', 'categories.parent_id as parent_category_id', 'categories.title as category_title', 'categories.slug', DB::raw("count(categories.id) as count"))
            ->join('category_course', 'category_course.category_id', '=', 'categories.id')
            ->where('parent_id' , '!=','0')
            ->whereIn('category_course.course_id', $courses->pluck('id')->toArray())
            ->whereNotNull('categories.id')
            ->groupBy('categories.id')
            ->get();

        if($combined) {
            return array_merge($course_categories->toArray(), $course_subcategories->toArray());
        }
        return $this->showSubCategoriesWithParentCategories($course_categories, $course_subcategories);
   }
}
