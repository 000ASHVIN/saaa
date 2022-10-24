<?php

namespace App\Http\Controllers;

use App\Card;
use App\Peach;
use App\Video;
use Carbon\Carbon;
use App\Billing\Item;
use App\Blog\Category;
use App\Http\Requests;
use http\Env\Response;
use App\Billing\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Donation;
use App\Repositories\Donation\DonationRepository;
use App\ActivityLog;

class WebinarsOnDemandController extends Controller
{
    private $peach;

    public function __construct(Peach $peach)
    {
        $this->peach = $peach;
        $this->middleware('auth', ['except' => ['index', 'show', 'search','category','addToCart','searchWebinars', 'videoCategory', 'webinar_type']]);
    }

    public function index()
    {
        $videos = Video::where('tag', 'studio')->whereNull('reference_id')->orderBy('id', 'desc')->paginate(6);
        $categories = $this->getCategoriesByParent(0);

        // for best seller
        $items = Item::select('item_id', DB::raw('count(item_id) as count_item_id'))
            ->where('item_type', 'App\Video')
            ->groupBy('item_id')
            ->orderBy('count_item_id', 'desc')
            ->paginate(10);
        $best_seller = [];
        foreach($items as $item) {
            if($item->item_id != 0) {
                $best_seller[] = Video::find($item->item_id);
            }
        }
        $best_seller = array_slice($best_seller, 0, 4);
        // end

        //for new release 
        $new_releases = Video::where('tag', 'studio')
            ->where('status',0)
            ->whereNull('reference_id')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        //end

        //for webinar series
        $webinar_series = Video::where('tag', 'studio')
            ->where('type', 'series')
            ->whereNull('reference_id')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        //end

        //for all webinar on demand
        $all_webinar = Video::where('tag', 'studio')
            ->where('status',0)
            ->whereNull('reference_id')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        //end

        //for free webinar series
        $free_webinar = Video::where('tag', 'studio')
            ->where('status',0)
            ->where('amount', 0)
            ->whereNull('reference_id')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        //end

        return view('webinars_on_demand.index', compact('videos', 'categories', 'best_seller', 'new_releases', 'webinar_series', 'all_webinar', 'free_webinar'));
    }

    public function webinar_type($type) {
        $webinar_title = '';
       
        if($type == 'best_seller') {
            $webinar_title = "Best Sellers";
            $videos = Video::select('videos.*', DB::raw('count(items.item_id) as count_item_id'))
                ->join('items', 'videos.id', '=', 'items.item_id')
                ->where('items.item_type', 'App\Video')
                ->whereNull('reference_id')
                ->groupBy('items.item_id')
                ->orderBy('count_item_id', 'desc')
                ->paginate(8);
        }
        
        if($type == 'new_releases') {
            $webinar_title = "New Releases";
            $videos = Video::where('tag', 'studio')
                ->where('status',0)
                ->whereNull('reference_id')
                ->orderBy('created_at', 'desc')
                ->paginate(8);
        }

        if($type == 'webinar_series') {
            $webinar_title = 'Webinar Series';
            $videos = Video::where('type', 'series')
                ->whereNull('reference_id')
                ->orderBy('created_at', 'desc')
                ->paginate(8);  
        }

        if($type == 'all_webinar') {
            $webinar_title = 'All Webinars On Demand';
            $videos = Video::where('tag', 'studio')
                ->where('status',0)
                ->whereNull('reference_id')
                ->orderBy('created_at', 'desc')
                ->paginate(8);  
        }

        if($type == 'free_webinar') {
            $webinar_title = 'Free Webinars On-Demand';
            $videos = Video::where('tag', 'studio')
                ->where('status',0)
                ->where('amount', 0)
                ->whereNull('reference_id')
                ->orderBy('created_at', 'desc')
                ->paginate(8);  
        }

        $categories = $this->getCategoriesByParent(0);
        return view('webinars_on_demand.show_all', compact('videos', 'categories', 'webinar_title'));
    }

    public function getCategoriesByParent($parent_id=null, $is_dashboard = false) {

        $categories = collect();
        $topics = Category::with('parent', 'parent.parent')
            ->select('categories.*')
            ->join('videos', 'categories.id', '=', 'videos.category');

        // Check for pratice plan
        if($is_dashboard) {

            $is_practice_package = auth()->user()->subscription('cpd')->plan->is_practice;
            if($is_practice_package) {
                $topics->join('feature_video', 'videos.id', '=', 'feature_video.video_id')
                ->join('practice_plan_tabs_features', 'feature_video.feature_id','=','practice_plan_tabs_features.feature_id');
            }
        }
            
        $topics = $topics->where('videos.tag', 'studio')
            ->where('videos.status',0)
            // ->where('videos.type','single')
            ->groupBy('categories.id')
            ->get();
        
        // All categories with videos
        foreach($topics as $topic) {

            $categories->push($topic);
            if($topic->parent) {

                $categories->push($topic->parent);

                if($topic->parent->parent) {
                    $categories->push($topic->parent->parent);
                }
            }
        }
        
        // Filter if parent category id available
        if($parent_id!==null) {
            $categories = $categories->filter( function($cat) use($parent_id){
                if($cat->parent_id==$parent_id) {
                    return true;
                }
                return false;
            });
        }

        $categories=$categories->unique('id');
        $categories->each(function($category, $key) {
            $category->title = trim($category->title);
            return $category;
        });
        $categories=$categories->sortBy('title');


        return $categories;

    }

    public function show($slug)
    {

        $categories = Category::where ('parent_id',0)->get();
        
        $video = Video::where('slug', $slug)->where('status','0')->whereNull('reference_id')->first();
        if(!$video)
        {
            alert()->error('We did not find any Webinar on Demand ', 'No Webinar On Demand Found');
            return redirect('/');
        }
        if($video->type=='series') {
            $related = Video::where('tag', 'studio')->where('status','0')->whereNull('reference_id')->where('type', 'series')->get();
        }
        else {
            $related = Video::where('tag', 'studio')->where('status','0')->whereNull('reference_id')->where('category', $video->category)->get();
        }
        return view('webinars_on_demand.show', compact('video', 'related', 'categories'));
    }

    public function checkout($slug)
    {

        if(request()->has('threeDs'))
            $this->handleThreeDs(request());

            $video = Video::with(['pricings', 'pricings.features','categories'])->whereNull('reference_id')->where('slug', $slug)->first();
            $categories = array_keys(Video::where('tag', 'studio')->whereNull('reference_id')->get()->groupBy('category')->toArray());

            if (auth()->user()->webinars->contains($video->id)){
                alert()->warning('Unable to complete the checkout because you already have this video available in your video library.', 'Warning')->persistent('Close');
                return redirect()->back();
            }

        return view('webinars_on_demand.checkout', compact('video', 'categories'));
    }

    public function post_checkout(Request $request)
    {
        return DB::transaction(function() use ($request) {

            $video = Video::find($request['video']);
            $card = Card::find($request['card']);
            $donations = $request['donations'];


            if(($video->amount + $donations) == 0) {
                $this->assignWebinarOnDemand($video);
                return response()->json(['message' => 'success'], 200);
            }
            
            $invoice = $this->generateInvoice(auth()->user(), $video, $request);

            $invoice->addItems($this->products);
            $invoice->autoUpdateAndSave();

            if($request->paymentOption == 'eft') {
                $invoice->settle();
                $this->allocatePayment($invoice, $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount'), "Instant EFT Payment", 'instant_eft');

                $this->assignWebinarOnDemand($video);
                $invoice = $invoice->fresh();
                $items = $invoice->items;
                return response()->json(['message' => 'success', 'invoice' => $invoice], 200);
            }

            if($request->paymentOption == 'cc') {
                $payment = $this->peach->charge(
                    $card->token,
                    $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount'),
                    '#' . $invoice->reference,
                    $invoice->reference
                );

                if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {
                    $invoice->settle();
                    $this->allocatePayment($invoice, $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount'), "Credit Card Payment", 'cc');

                    $this->assignWebinarOnDemand($video);
                    $invoice = $invoice->fresh();
                    $items = $invoice->items;
                    return response()->json(['message' => 'success', 'invoice' => $invoice], 200);
                } else {
                    return response()->json([
                        'errors' => $payment['result']['description']
                    ], 422);
                }
                return response()->json(['errors' => $video], 422);
            }
        });
    }

    protected function handleThreeDs($request)
    {
        $payment = $this->peach->fetchPayment($request->id);

        if(! Card::where('token', $payment->registrationId)->exists() && $payment->successful()) {
            $card = new Card([
                'token' => $payment->registrationId,
                'brand' => $payment->paymentBrand,
                'number' => $payment->card['bin'] . '******' . $payment->card['last4Digits'],
                'exp_month' => $payment->card['expiryMonth'],
                'exp_year' => $payment->card['expiryYear']
            ]);

            auth()->user()->cards()->save($card);

            if(count(auth()->user()->cards) == 1) {
                auth()->user()->update([
                    'primary_card' => $card->id
                ]);
            }

            alert()->success('Credit card added successfully.', 'Success');
        } else {
            alert()->error('Credit card already added or invalid.', 'Could not save credit card');
        }
    }

    public function generateInvoice($user, $video, $request)
    {
        $invoice = new Invoice;
        $invoice->type = 'store';
        $invoice->setUser($user);

        /*
        * Add donations if exists
        */
        $donations = $request->donations;
        if($donations) {
            $invoice->donation = $donations;
        }

        $invoice->save();

        $item = new Item;
        $item->type = 'webinar';
        $item->name = $video->title;
        $item->description = 'Webinar On-Demand';
        $item->price = $video->amount;
        $item->discount = '0';
        $item->item_id = $video->id;
        $item->item_type = get_class($video);
        $item->save();

        $this->products[] = $item;
        return $invoice;
    }

    public function allocatePayment($invoice, $amount, $description, $method)
    {
        $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'credit',
            'display_type' => 'Payment',
            'status' => 'Closed',
            'category' => $invoice->type,
            'amount' => $amount,
            'ref' => $invoice->reference,
            'method' => $method,
            'description' => $description,
            'tags' => "Payment",
            'date' => Carbon::now()->addSeconds(30)
        ]);
    }

    public function search(Request $request, $api = '')
    {
        $categories = $this->getCategoriesByParent(0);
        $title = $request['title'];
        $category = $request['category'];
        $cpd = $request['cpd'];
        $sub_category = $request['sub_category'];
        $sub_sub_category = $request['sub_sub_category'];
        $category_ids = [];

        if($sub_sub_category && $sub_sub_category!= 'null') {
            $category_ids[] = $sub_sub_category;

        } elseif($sub_category && $sub_category!= 'null') {
            $category_ids = $this->get_categories($sub_category);
            array_push($category_ids,$sub_category);

        } elseif($category && $category!= 'null') {
            $category_ids = $this->get_categories($category);
            array_push($category_ids,$category);
        }

        $videos = Video::whereRaw(1);
        if (strlen($title)) {        
            $videos->search($title, null, true);
            $videos=$videos->orderByRaw('relevance desc,id desc');
        }
        else {
            $videos=$videos->orderBy('id','desc');
        }
        if(count($category_ids)) {
            $videos->whereIn('category', $category_ids);
        }
        if ($cpd) {
            $videos->where('hours', 'like', '%' . $cpd . '%');
        }

        $videos->where('tag', 'studio')
        ->whereNull('reference_id')
            ->where('status','0');
            // ->where('type','single');;
        $videos=$videos->get();
        
        if($api == 'category') {
            return view('webinars_on_demand.search_category', compact('videos', 'sub_category', 'sub_sub_category', 'title', 'category', 'cpd', 'categories'));
        }
        else {
            return view('webinars_on_demand.search_results', compact('videos', 'sub_category', 'sub_sub_category', 'title', 'category', 'cpd', 'categories'));
        }
    }

    protected function get_categories($cat) {
        $arrCategory=[];
        $category = Category::find($cat);
        $childCategories = $category->childCategory();

        // If child categories exists than call recursive function
        foreach($childCategories as $cat) {
            $arrCategory[] = (string)$cat->id;
            $arrCategory = array_merge($arrCategory, $this->get_categories($cat->id));
        }
        return $arrCategory;
    }

    public function assignWebinarOnDemand($video) {

        $user = auth()->user();

        if($user) {

            $allVideos = collect();
            if($video->type=='series') {

                // User's owned webinars
                $owned_webinars = [];
                $owned_webinars = $user->webinars->pluck('id')->toArray();

                foreach($video->webinars as $value) {
                    if(!in_array($value->id,$owned_webinars)) {
                        $allVideos->push($value);
                    }
                }

                $webinars = $allVideos->pluck('id')->toArray();
                $webinars[] = $video->id;
                $user->webinars()->attach($webinars);

                foreach($allVideos as $v) {
                    $v->calculateWebinarComplete($user);
                }

            }
            else {
                $allVideos->push($video);
                $user->webinars()->save($video);
                $video->calculateWebinarComplete($user);
            }

            // Assign cpd hours and certificate for all videos
            // foreach($allVideos as $v) {
            //     $cpd = $this->assignCPDHours($user, $v->hours, 'Webinars On Demand - '.$v->title, null, $v->category, false);
            //     $this->assignCertificate($cpd, $v);
            // }
        }

    }

    public function assignCPDHours($user, $hours, $source, $attachment, $category, $verifiable)
    {
        $cpd = $user->cpds()->create([
            'date' => Carbon::now(),
            'hours' => $hours,
            'source' => $source,
            'attachment' => $attachment,
            'category' => $category,
            'verifiable' => $verifiable,
        ]);
        return $cpd;
    }
    public function category(Request $request)
    {
        if($request->ajax()){

            $category = $request->category;
            $categories =  Category::where('parent_id',$category)->get()->pluck('title','id');
            if (count($categories)){
                return response()->json($categories);
            }
        }
    }

    public function videoCategory(Request $request)
    {
        if($request->ajax()){
            $category = $request->category;
            $is_dashboard = ($request->is_dashboard)?true:false;
            $categories = $this->getCategoriesByParent($category, $is_dashboard);

            $response = collect();
            foreach($categories as $c) {
                $cat = [];
                $cat['title'] = $c->title;
                $cat['id'] = $c->id;
                $response->push($cat);
            }

            if (count($categories)){
                return response()->json($response);
            }
        }
    }

    public function assignCertificate($cpd, $video)
    {
        $video = Video::where('title', 'LIKE', '%'.substr($cpd->source, 28).'%')->get()->first();
        if ($video){
            $cpd->certificate()->create([
                'source_model' => Video::class,
                'source_id' => $video->id,
                'source_with' => [],
                'view_path' => 'certificates.wob'
            ]);
        }
    }

    public function addToCart(Request $request,$slug)
    {
        try{
            $video = Video::where('slug', $slug)->first();
            $video->addToCart();
            return redirect()->back();
        }catch(\Exception $e){

        }
    }

    public function searchWebinars() {
        return redirect()->route('webinars_on_demand.home');
    }

    public function webinars_on_demand_play(Request $request)
    {
        if($request->ajax()){
            $ActivityLog = ActivityLog::create([
                    'user_id'=> (auth()->check())?auth()->user()->id:0,
                    'model'=> get_class(new Video()),
                    'model_id'=> isset($request->video_id)?$request->video_id:0,
                    'action_by'=> 'manually',
                    'action'=> 'watched',
                    'data'=> json_encode(request()->all()),
                    'request_url'=> request()->path()
            ]);
            return response()->json(['message'=>'Activity log created successfully','status'=>'1']);
        }
    }

}
