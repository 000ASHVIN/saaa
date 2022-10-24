<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Video;
use Carbon\Carbon;
use App\Assessment;
use App\Blog\Category;
use GuzzleHttp\Client;
use App\AppEvents\Event;
use App\AppEvents\Venue;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Link as EventLink;
use App\Assessments\Option;
use App\Assessments\Question;
use App\AppEvents\Extra;
use App\AppEvents\PromoCode;
use App\AppEvents\Webinar;
use App\Recording;
use YAAP\Theme\Facades\Theme;

class AdminEventSyncController extends Controller
{
    private $eventRepository, $venueRepository;
    // public function __construct(EventRepository $eventRepository, VenueRepository $venueRepository)
    // {
    //     $this->eventRepository = $eventRepository;
    //     $this->venueRepository = $venueRepository;
    // }

    // Get all event list
    public function getEventList()
    {
        // call TTF events api
        $client = new Client([
            'base_uri' => env('EVENT_API'),
        ]);
        $response = $client->get('api/event-list');
        $body = $response->getBody();
        $api_events = json_decode((string) $body);

        // First option for list of sync event
            // $newArray = array();
            // $events = Event::all();

            // foreach($events as $e){
            //     foreach($api_events as $event){
            //         if($e->name == $event->name)
            //         {
            //             $newArray[] = $event->name;
            //         }
            //     }
            // }
            // dd($newArray);

        // Second option for list of sync event
        $eventNameArray = array();
        $apiEventNameArray = array();
        $events = Event::all();

        foreach($events as $e){
            $eventNameArray[] = $e->name;
        }
        foreach($api_events as $event){
            $apiEventNameArray[] = $event->name;  
        }
        $syncedEvent = array_diff($apiEventNameArray, $eventNameArray);
        $syncedEventList = array_intersect($eventNameArray, $apiEventNameArray);
       
        return view('admin.events.event_list', compact('api_events','syncedEventList','syncedEvent'));
    }

    public function checkevent(Request $request){
        $api_event = $request->name;
        $event = Event::where('name',$api_event)->first();
        if($event){
            return 'Event Exist';
        }
        return 'New Event';
    }
    public function UpdateNewEvent(Request $request,$name)
    {
        try
        {
            $request = request();
            $request->merge(['event'=>$name]); 
            $this->getEventListSync($request);
        }catch(\Exception $e)
        {

        }

    }

 // Add saaa plan list to ttf plan list
    public function getEventListSync(Request $request)
    {
        DB::transaction(function () use($request){
        // Event , Venue , Pricing , Dates , Links
        $api_event = $request->event;
        $event = Event::with('presenters','venues','venues.pricings', 'venues.pricings.recordings', 'venues.pricings.recordings.video', 'venues.pricings.recordings.video.categories', 'links','files','venues.dates','extras','assessments','assessments.questions','assessments.questions.options','venues.pricings.webinars')->withTrashed()->where('reference_id',$request->reference_id)->get();
        $client = new Client([
            'base_uri' => env('EVENT_API'),
        ]);
        
        $response = $client->get('api/event/'.$request->reference_id);
        $event_data = json_decode($response->getBody()->getContents());

        // Api event is already in Events  
        if($event->count() > 0)
        {

            if($event[0]->trashed())
			{
				$event[0]->restore();
			}
            
            // Update Event
            foreach($event_data as $data){
                $events = $event->where('reference_id',$data->id);
                foreach($events as $eventupdate){
                    $Event_start_date = Carbon::parse($data->start_date )->format('Y-m-d');
                    $Event_end_date = Carbon::parse($data->end_date )->format('Y-m-d');
                    
                        $eventupdate->update([
                            'type' => $data->type,
                            'name' => $data->name,
                            'start_date' => new Carbon($data->start_date),
                            'end_date' => new Carbon($data->end_date),
                            'next_date' => new Carbon($data->next_date),
                            'start_time' => Carbon::createFromTimestamp(strtotime($Event_start_date . $data->start_time . ':00')),
                            'end_time' => Carbon::createFromTimestamp(strtotime($Event_end_date . $data->end_time . ':00')),
                            'published_at' => new Carbon($data->published_at),
                            'is_active' => $data->is_active,
                            'category' => $data->category,
                            // 'description' => $data->description,
                            'is_redirect' => $data->is_redirect,
                            'redirect_url' => $data->redirect_url,
                            'featured_image' => $data->featured_image,
                            // 'short_description' => $data->short_description,
                            'is_open_to_public' => $data->is_open_to_public,
                            'subscription_event' => $data->subscription_event,
                            'registration_instructions' => $data->registration_instructions,
                            'video_title' => $data->video_title,
                            'video_url' => $data->video_url,
                            'background_url' => $data->background_url,
                            'list_id' => $data->list_id,
                            'keyword' => $data->keyword,
                            'meta_description' => $data->meta_description
                        ]);
                // Update event end

                // link update
                if (count($data->links)){
                    $synced_ids = [];
                    foreach ($data->links as $link){
                        if(count($eventupdate->links)){
                            foreach($eventupdate->links as $event_link){
                                if($link->name == $event_link->name){
                                    $event_link->update([
                                        'name' => $link->name,
                                        'url' => $link->url,
                                        'instructions' => $link->instructions,
                                        'secret' => $link->secret
                                    ]);
                                    $synced_ids[] = $event_link->id;
                                }
                            }
                        }

                        $is_exist = $eventupdate->links()->where('name', $link->name)->first();
                        if(!$is_exist) {
                            $linkdata = EventLink::create([
                                'name' => isset($link->name)?$link->name:'',
                                'url' => isset($link->url)?$link->url:'',
                                'instructions' => isset($link->instructions)?$link->instructions:'',
                                'secret' => isset($link->secret)?$link->secret:''
                            ]);
                            $eventupdate->links()->save($linkdata);
                            $synced_ids[] = $linkdata->id;
                        }
                    }
                    if(count($eventupdate->links)){
                        foreach($eventupdate->links as $event_link){
                            if(! in_array($event_link->id, $synced_ids)) {
                                $event_link->delete();
                            }
                        }
                    }
                }
                // End link update


                // Extras update
                if (count($data->extras)){
                    $synced_ids = [];
                    foreach ($data->extras as $extra){
                        if(count($eventupdate->extras)){
                            foreach($eventupdate->extras as $event_extra){
                                if($extra->name == $event_extra->name){
                                    $event_extra->update([
                                        'is_active' => $extra->is_active,
                                        'name' => $extra->name,
                                        'price' => $extra->price,
                                        'cpd_hours' => $extra->cpd_hours,
                                    ]);
                                    $synced_ids[] = $event_extra->id;
                                }
                            }
                        }
                        
                        $is_exist = $eventupdate->extras()->where('name', $extra->name)->first();
                        if(!$is_exist) {
                            $new_extra = Extra::create([
                                'is_active' => isset($extra->is_active)?$extra->is_active:'',
                                'name' => isset($extra->name)?$extra->name:'',
                                'price' => isset($extra->price)?$extra->price:'',
                                'cpd_hours' => isset($extra->cpd_hours)?$extra->cpd_hours:'',
                            ]);
                            $eventupdate->extras()->save($new_extra);
                            $synced_ids[] = $new_extra->id;
                        }
                    } 
                    
                    if(count($eventupdate->extras)){
                        foreach($eventupdate->extras as $event_extra){
                            if(! in_array($event_extra->id, $synced_ids)) {
                                $event_extra->delete();
                            }
                        }
                    }
                    
                }
                // End extras update

                // Assessments update
                if (count($data->assessments)){
                    $synced_ids = [];
                    foreach ($data->assessments as $assessment){
                        if(count($eventupdate->assessments)){
                            foreach($eventupdate->assessments as $event_assessment){
                                if($assessment->title == $event_assessment->title){
                                    $event_assessment->update([
                                        // 'guid' => $assessment->guid.'_'.time(),
                                        'title' => $assessment->title,
                                        'instructions' => $assessment->instructions,
                                        'cpd_hours' => $assessment->cpd_hours,
                                        'pass_percentage' => $assessment->pass_percentage,
                                        'time_limit_minutes' => $assessment->time_limit_minutes,
                                        'maximum_attempts' => $assessment->maximum_attempts,
                                        'randomize_questions_order' => $assessment->randomize_questions_order,
                                    ]);
                                    $synced_ids[] = $event_assessment->id;
                                    if (count($assessment->questions)){
                                        $this->updateQuestions($assessment, $event_assessment);
                                    }
                                }
                            }
                        }

                        $is_exist = $eventupdate->assessments()->where('title', $assessment->title)->first();
                        if(!$is_exist) {
                            $event_assessment = $this->createAssessment($assessment);
                            $eventupdate->assessments()->save($event_assessment);
                            if (count($assessment->questions)){
                                $this->updateQuestions($assessment, $event_assessment);
                            }
                            $synced_ids[] = $event_assessment->id;
                        }
                    } 
                    $eventupdate->assessments()->sync($synced_ids);
                }
                // End assessments update


                // Venue update
                if (count($data->venues)){
                        
                    foreach ($data->venues as $venue){
                        if(count($eventupdate->venues)){
                            foreach($eventupdate->venues as $event_venue){
                                if($venue->name == $event_venue->name){
                                    $event_venue->update([
                                        'name' => $venue->name,
                                        'type' => $venue->type,
                                        'city' => $venue->city,
                                        'country' => $venue->country,
                                        'province' => $venue->province,
                                        'is_active' => $venue->is_active,
                                        'area_code' => $venue->area_code,
                                        'address_line_two' => $venue->address_line_two,
                                        'address_line_one' => $venue->address_line_one,
                                        'start_time' => $venue->start_time,
                                        'end_time' => $venue->end_time,
                                        'google_maps_embed_url' => $venue->google_maps_embed_url,
                                        'image_url' => $venue->image_url,
                                        'max_attendees' => $venue->max_attendees,
                                        'min_attendees' => $venue->min_attendees,
                                    ]);
                                    $event_venue->is_synced = 1;

                                    $date = Date::where('venue_id',$event_venue->id)->get();
                                    if(count($date)) {
                                        foreach($date as $event_date){
                                            $event_date->delete();
                                        }
                                    }
                                    if(count($venue->dates)){
                                        foreach($venue->dates as $date){
                                            $event_venue->dates()->save(new Date([
                                                'date' => Carbon::parse($date->date),
                                                'is_active' => $date->is_active
                                            ]));
                                        }
                                    }

                                    // Pricing update
                                    $pricing = Pricing::where('event_id',$eventupdate->id)->where('venue_id',$event_venue->id)->get();
                                    
                                    $synced_ids = [];
                                    foreach ($venue->pricings as $price){
                                        if(count($pricing)){
                                            foreach($pricing as $event_price){
                                                if($price->name == $event_price->name)
                                                {
                                                    $event_price->update([
                                                            'start_time' => $price->start_time,
                                                            'end_time' => $price->end_time,
                                                            'name' => $price->name,
                                                            'price' => $price->price,
                                                            'is_active' => $price->is_active,
                                                            'cpd_hours' => $price->cpd_hours,
                                                            'day_count' => $price->day_count,
                                                            'description' => $price->description,
                                                            'can_manually_claim_cpd' => $price->can_manually_claim_cpd,
                                                            'cpd_verifiable' => $price->cpd_verifiable,
                                                            'attendance_certificate' => $price->attendance_certificate
                                                    ]);
                                                    $synced_ids[] = $event_price->id;

                                                    $synced_webinar_ids = [];
                                                    if(count($price->webinars)) {
                                                        
                                                        foreach($price->webinars as $webinars)
                                                        {
                                                            $isExist = Webinar::where('pricing_id',$event_price->id)->where('url',$webinars->url)->first();
                                                            if($isExist)
                                                            {
                                                                $newWebinar =$isExist;
                                                            }else
                                                            {
                                                                $newWebinar = new Webinar();
                                                            }
                                                            $newWebinar->url = $webinars->url;
                                                            $newWebinar->code = $webinars->code;
                                                            $newWebinar->passcode = $webinars->passcode;
                                                            $newWebinar->is_active = $webinars->is_active;
                                                            $newWebinar->pricing_id = $event_price->id;
                                                            $newWebinar->save();

                                                            $synced_webinar_ids[] = $newWebinar->id;
                                                        }
                                                    }
                                                    if(count($event_price->webinars)){
                                                        foreach($event_price->webinars as $webinar){
                                                            if(! in_array($webinar->id, $synced_webinar_ids)) {
                                                                $webinar->delete();
                                                            }
                                                        }
                                                    }

                                                    $synced_video_ids = [];
                                                    if(count($price->recordings)) {
                                                        foreach($price->recordings as $recording) {
                                                            if(count($event_price->recordings)) {
                                                                foreach($event_price->recordings as $event_recording) {
                                                                    $video = $recording->video;
                                                                    $event_video = $event_recording->video;
                                                                    if($video && $event_video) {
                                                                        if($video->id == $event_video->reference_id) {
                                                                            $videoCategory = $video->categories;
                                                        
                                                                            $category_id = null;
                                                                            if($videoCategory) {
                                                                                $category = Category::where('title', $videoCategory->title)->first();
                                                                                
                                                                                if($category) {
                                                                                    $category_id = $category->id;
                                                                                } else {
                                                                                    $newCategory = Category::create([
                                                                                        'title' => $videoCategory->title,
                                                                                        'description' => $videoCategory->description
                                                                                    ]);
                                                                                    $category_id = $newCategory->id;
                                                                                }
                                                                            }
                                                        
                                                                            $event_video->update([
                                                                                'title' => $video->title,
                                                                                'reference' => $video->reference,
                                                                                'video_provider_id' => $video->video_provider_id,
                                                                                'download_link' => $video->download_link,
                                                                                'width' => $video->width,
                                                                                'height' => $video->height,
                                                                                'can_be_downloaded' => $video->can_be_downloaded,
                                                                                'view_link' => $video->view_link,
                                                                                'category' => $category_id,
                                                                                'hours' => $video->hours,
                                                                                'tag' => $video->tag,
                                                                                'status' => $video->status,
                                                                                'amount' => $video->amount,
                                                                                'description' => $video->meta_description,
                                                                                'view_resource' => $video->view_resource,
                                                                                'width' => '0',
                                                                                'height' => '0',
                                                                                'cover' => (env('APP_THEME') == 'taxfaculty' ? Theme::asset('/img/player.jpg') : "https://imageshack.com/a/img924/9673/A4DN1j.jpg"),
                                                                                'reference_id' => $video->id
                                                                            ]);
                                                                    
                                                                            // $newVideo->features()->sync(! $newVideo['VideoFeaturesList'] ? [] : $newVideo['VideoFeaturesList']);
                                                                            // $newVideo->presenters()->sync(! $newVideo['VideoPresentersList'] ? [] : $newVideo['VideoPresentersList']);
                                                        
                                                                            // $recording = new Recording();
                                                                            // $recording->pricing_id = $pricedata->id;
                                                                            // $recording->video_id = $newVideo->id;
                                                                            // $recording->save();

                                                                            $synced_video_ids[] = $event_video->id;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            $video = $recording->video;
                                                            if($video) {
                                                                $system_video = Video::where('reference_id', $video->reference_id)->first();
                                                                if($system_video) {
                                                                    $isExist = Recording::where('pricing_id',$event_price->id)->where('video_id',$system_video->id)->first();
                                                                    if(!$isExist) {
                                                                        $this->addRecordingsToPricing($price, $event_price);
                                                                    }
                                                                } else {
                                                                    $this->addRecordingsToPricing($price, $event_price);
                                                                }
                                                                
                                                            }
                                                        }
                                                    }
                                                    if(count($event_price->recordings)) {
                                                        foreach($event_price->recordings as $event_recording) {
                                                            if(! in_array($event_recording->video_id, $synced_video_ids)) {
                                                                $event_recording->delete();
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        
                                        $price_venue = $eventupdate->venues()->where('name', $venue->name)->first();
                                        if($price_venue) {
                                            $is_already_created = Pricing::where('event_id',$eventupdate->id)->where('venue_id',$price_venue->id)->where('name',$price->name)->first();
                                            
                                            if(!$is_already_created) {

                                                $pricedata = Pricing::create([
                                                    'event_id' => $eventupdate->id,
                                                    'venue_id' => $price_venue ? $price_venue->id : $event_venue->id,
                                                    'start_time' => isset($price->start_time)?$price->start_time:'',
                                                    'end_time' => isset($price->end_time)?$price->end_time:'',
                                                    'name' => isset($price->name)?ucfirst($price->name):'',
                                                    'price' => isset($price->price)?$price->price:'',
                                                    'is_active' => isset($price->is_active)?$price->is_active:'',
                                                    'cpd_hours' => isset($price->cpd_hours)?$price->cpd_hours:'',
                                                    'day_count' => isset($price->day_count)?$price->day_count:'',
                                                    'description' => isset($price->description)?$price->description:'',
                                                    'can_manually_claim_cpd' => isset($price->can_manually_claim_cpd)?$price->can_manually_claim_cpd:'',
                                                    'cpd_verifiable' => isset($price->cpd_verifiable)?$price->cpd_verifiable:'',
                                                    'attendance_certificate' => isset($price->attendance_certificate)?$price->attendance_certificate:''
                                                ]);
                                                if(count($price->webinars)) {
                                                    foreach($price->webinars as $webinars)
                                                    {
                                                        $isExist = Webinar::where('pricing_id',$pricedata->id)->where('url',$webinars->url)->first();
                                                        if($isExist)
                                                        {
                                                            $newWebinar =$isExist;
                                                        }else
                                                        {
                                                            $newWebinar = new Webinar();
                                                        }
                                                        $newWebinar->url = $webinars->url;
                                                        $newWebinar->code = $webinars->code;
                                                        $newWebinar->passcode = $webinars->passcode;
                                                        $newWebinar->is_active = $webinars->is_active;
                                                        $newWebinar->pricing_id = $pricedata->id;
                                                        $newWebinar->save();
                                                    }
                                                }

                                                $this->addRecordingsToPricing($price, $pricedata);
                                            }
                                        }

                                    }

                                    if(count($pricing)){
                                        foreach($pricing as $event_price){
                                            if(! in_array($event_price->id, $synced_ids)) {
                                                $event_price->delete();
                                            }
                                        }
                                    }
                                    // End pricing update
                                }
                            }
                        }

                        $is_exist = $eventupdate->venues()->where('name', $venue->name)->first();
                        if(!$is_exist) {
                            $venuedata = Venue::create([
                                'name' => isset($venue->name)?$venue->name:'',
                                'type' => isset($venue->type)?$venue->type:'',
                                'city' => isset($venue->city)?$venue->city:'',
                                'country' => isset($venue->country)?$venue->country:'',
                                'province' => isset($venue->province)?$venue->province:'',
                                'is_active' => isset($venue->is_active)?$venue->is_active:'',
                                'area_code' => isset($venue->area_code)?$venue->area_code:'',
                                'address_line_two' => isset($venue->address_line_two)?$venue->address_line_two:'',
                                'address_line_one' => isset($venue->address_line_one)?$venue->address_line_one:'',
                                'start_time' => isset($venue->start_time)?$venue->start_time:'',
                                'end_time' => isset($venue->end_time)?$venue->end_time:'',
                                'google_maps_embed_url' => isset($venue->google_maps_embed_url)?$venue->google_maps_embed_url:'',
                                'image_url' => isset($venue->image_url)?$venue->image_url:'',
                                'max_attendees' => isset($venue->max_attendees)?$venue->max_attendees:'',
                                'min_attendees' => isset($venue->min_attendees)?$venue->min_attendees:'',
                            ]);
                            $eventupdate->venues()->attach($venuedata);
                            // Insert venue end
    
                            //  Insert dates  
                            if(isset($venue->dates)){
                                foreach($venue->dates as $date){
                                    $venuedata->dates()->save(new Date([
                                        'date' => Carbon::parse($date->date),
                                        'is_active' => $date->is_active
                                    ]));
                                }
                            }
                            // Insert date end
    
                            //  Insert pricing  
                            if(isset($venue->pricings)){
                                foreach($venue->pricings as $price){
                                    $pricedata = Pricing::create([
                                        'event_id' => $eventupdate->id,
                                        'venue_id' => $venuedata->id,
                                        'start_time' => isset($price->start_time)?$price->start_time:'',
                                        'end_time' => isset($price->end_time)?$price->end_time:'',
                                        'name' => isset($price->name)?ucfirst($price->name):'',
                                        'price' => isset($price->price)?$price->price:'',
                                        'is_active' => isset($price->is_active)?$price->is_active:'',
                                        'cpd_hours' => isset($price->cpd_hours)?$price->cpd_hours:'',
                                        'day_count' => isset($price->day_count)?$price->day_count:'',
                                        'description' => isset($price->description)?$price->description:'',
                                        'can_manually_claim_cpd' => isset($price->can_manually_claim_cpd)?$price->can_manually_claim_cpd:'',
                                        'cpd_verifiable' => isset($price->cpd_verifiable)?$price->cpd_verifiable:'',
                                        'attendance_certificate' => isset($price->attendance_certificate)?$price->attendance_certificate:''
                                    ]);

                                    if(count($price->webinars)) {
                                        foreach($price->webinars as $webinars)
                                        {
                                            $newWebinar = new Webinar();
                                            $newWebinar->url = $webinars->url;
                                            $newWebinar->code = $webinars->code;
                                            $newWebinar->passcode = $webinars->passcode;
                                            $newWebinar->is_active = $webinars->is_active;
                                            $newWebinar->pricing_id = $pricedata->id;
                                            $newWebinar->save();
                                        }
                                    }
                                    
                                    $this->addRecordingsToPricing($price, $pricedata);
                                }
                            }
                            // Insert pricing end
                        }
                        
                    }

                    if(count($eventupdate->venues)){
                        foreach($eventupdate->venues as $event_venue){
                            if(!(isset($event_venue->is_synced) && $event_venue->is_synced)) {
                                $event_venue->delete();

                                foreach($event_venue->pricings as $pricing){
                                    $pricing->delete();
                                }
                            }
                        }
                    }
                }
                // End venue update

                // Discount update
                // $synced_ids = [];
                // if (count($data->promo_codes)){
                    
                //     foreach ($data->promo_codes as $discount){
                //         if(count($eventupdate->promoCodes)){
                //             foreach($eventupdate->promoCodes as $event_discount){
                //                 if($discount->code == $event_discount->code){
                //                     $event_discount->update([
                //                         'title' => $discount->title,
                //                         'description' => $discount->description,
                //                         'code' => $discount->code,
                //                         'discount_type' => $discount->discount_type,
                //                         'discount_amount' => $discount->discount_amount,
                //                         'has_limited_uses' => $discount->has_limited_uses,
                //                         'remaining_uses' => $discount->remaining_uses
                //                     ]);
                //                     $synced_ids[] = $event_discount->id;
                //                 }
                //             }
                //         }
                        
                //         $is_exist = $eventupdate->promoCodes()->where('code', $discount->code)->first();
                //         if(!$is_exist) {
                //             $event_discount = $this->createDiscount($discount);
                //             $eventupdate->promoCodes()->save($event_discount);
                //             $synced_ids[] = $event_discount->id;
                //         }
                //     } 
                // }
                // if(count($eventupdate->promoCodes)){
                //     foreach($eventupdate->promoCodes as $event_discount){
                //         if(! in_array($event_discount->id, $synced_ids)) {
                //             $event_discount->delete();
                //         }
                //     }
                // }
                // End Discount update
                }
            }
        }
        // Api event not in Events
        else
        {
             // Insert
            foreach($event_data as $data)
            {
                $Event_start_date = Carbon::parse($data->start_date )->format('Y-m-d');
                $Event_end_date = Carbon::parse($data->end_date )->format('Y-m-d');
                // dd($data);
                // Insert Event
                $eventAdd = new Event([
                    'type' => isset($data->type)?$data->type:'',
                    'name' => isset($data->name)?$data->name:'',
                    'start_date' => new Carbon($data->start_date),
                    'end_date' => new Carbon($data->end_date),
                    'next_date' => new Carbon($data->next_date),
                    'start_time' => Carbon::createFromTimestamp(strtotime($Event_start_date . $data->start_time . ':00')),
                    'end_time' => Carbon::createFromTimestamp(strtotime($Event_end_date . $data->end_time . ':00')),
                    'published_at' => new Carbon($data->published_at),
                    'is_active' => isset($data->is_active)?$data->is_active:'',
                    'category' => isset($data->category)?$data->category:'',
                    'description' => isset($data->description)?$data->description:'',
                    'is_redirect' => isset($data->is_redirect)?$data->is_redirect:'',
                    'redirect_url' => isset($data->redirect_url)?$data->redirect_url:'',
                    'featured_image' => isset($data->featured_image)?$data->featured_image:'',
                    'short_description' => isset($data->short_description)?$data->short_description:'',
                    'is_open_to_public' => isset($data->is_open_to_public)?$data->is_open_to_public:'',
                    'subscription_event' => isset($data->subscription_event)?$data->subscription_event:'',
                    'registration_instructions' => isset($data->registration_instructions)?$data->registration_instructions:'',
                    'video_title' => isset($data->video_title)?$data->video_title:'',
                    'video_url' => isset($data->video_url)?$data->video_url:'',
                    'background_url' => isset($data->background_url)?$data->background_url:'',
                    'list_id' => isset($data->list_id)?$data->list_id:'',
                    // 'published_at' => isset($data->published_at)?$data->published_at:'',
                    'keyword' => isset($data->keyword)?$data->keyword:'',
                    'meta_description' => isset($data->meta_description)?$data->meta_description:'',
                    'reference_id' => isset($data->id)?$data->id:'',
                ]);
                $eventAdd->save();
                // Insert event end

                // Insert links
                if (count($data->links)){
                    foreach ($data->links as $link){
                        $linkdata = EventLink::create([
                            'name' => isset($link->name)?$link->name:'',
                            'url' => isset($link->url)?$link->url:'',
                            'instructions' => isset($link->instructions)?$link->instructions:'',
                            'secret' => isset($link->secret)?$link->secret:''
                        ]);
                        $eventAdd->links()->save($linkdata);
                        // $eventAdd->links()->attach($linkdata);
                    } 
                }
                // Insert link end

                 // Insert extras
                if (count($data->extras)){
                    foreach ($data->extras as $extra){
                        $extra = Extra::create([
                            'is_active' => isset($extra->is_active)?$extra->is_active:'',
                            'name' => isset($extra->name)?$extra->name:'',
                            'price' => isset($extra->price)?$extra->price:'',
                            'cpd_hours' => isset($extra->cpd_hours)?$extra->cpd_hours:'',
                        ]);
                        $eventAdd->extras()->save($extra);
                    } 
                } 
                // Insert extras end

                // Insert assessments
                if (count($data->assessments)){
                    foreach ($data->assessments as $assessment){
                        $new_assessment =  $this->createAssessment($assessment);
                        $eventAdd->assessments()->save($new_assessment);

                        if (count($assessment->questions)){
                            $this->updateQuestions($assessment, $new_assessment);
                        }
                    } 
                } 
                // Insert assessments end


                // Insert venue
                if(count($data->venues)){
                    foreach($data->venues as $venue){
                        $venuedata = Venue::create([
                            'name' => isset($venue->name)?$venue->name:'',
                            'type' => isset($venue->type)?$venue->type:'',
                            'city' => isset($venue->city)?$venue->city:'',
                            'country' => isset($venue->country)?$venue->country:'',
                            'province' => isset($venue->province)?$venue->province:'',
                            'is_active' => isset($venue->is_active)?$venue->is_active:'',
                            'area_code' => isset($venue->area_code)?$venue->area_code:'',
                            'address_line_two' => isset($venue->address_line_two)?$venue->address_line_two:'',
                            'address_line_one' => isset($venue->address_line_one)?$venue->address_line_one:'',
                            'start_time' => isset($venue->start_time)?$venue->start_time:'',
                            'end_time' => isset($venue->end_time)?$venue->end_time:'',
                            'google_maps_embed_url' => isset($venue->google_maps_embed_url)?$venue->google_maps_embed_url:'',
                            'image_url' => isset($venue->image_url)?$venue->image_url:'',
                            'max_attendees' => isset($venue->max_attendees)?$venue->max_attendees:'',
                            'min_attendees' => isset($venue->min_attendees)?$venue->min_attendees:'',
                        ]);
                        $eventAdd->venues()->attach($venuedata);
                        // Insert venue end

                        //  Insert dates  
                        if(isset($venue->dates)){
                            foreach($venue->dates as $date){
                                $venuedata->dates()->save(new Date([
                                    'date' => Carbon::parse($date->date),
                                    'is_active' => $date->is_active
                                ]));
                            }
                        }
                        // Insert date end

                        //  Insert pricing  
                        if(isset($venue->pricings)){
                            foreach($venue->pricings as $price){
                                $pricedata = Pricing::create([
                                    'event_id' => $eventAdd->id,
                                    'venue_id' => $venuedata->id,
                                    'start_time' => isset($price->start_time)?$price->start_time:'',
                                    'end_time' => isset($price->end_time)?$price->end_time:'',
                                    'name' => isset($price->name)?ucfirst($price->name):'',
                                    'price' => isset($price->price)?$price->price:'',
                                    'is_active' => isset($price->is_active)?$price->is_active:'',
                                    'cpd_hours' => isset($price->cpd_hours)?$price->cpd_hours:'',
                                    'day_count' => isset($price->day_count)?$price->day_count:'',
                                    'description' => isset($price->description)?$price->description:'',
                                    'can_manually_claim_cpd' => isset($price->can_manually_claim_cpd)?$price->can_manually_claim_cpd:'',
                                    'cpd_verifiable' => isset($price->cpd_verifiable)?$price->cpd_verifiable:'',
                                    'attendance_certificate' => isset($price->attendance_certificate)?$price->attendance_certificate:''
                                ]);

                                $this->addRecordingsToPricing($price, $pricedata);
                            }
                        }
                        // Insert pricing end
                }

                // Insert discounts
                // if (count($data->promo_codes)){
                //     foreach ($data->promo_codes as $discount){
                //         $newDiscount = $this->createDiscount($discount);
                //         $eventAdd->promoCodes()->save($newDiscount);
                //     } 
                // } 
                // Insert discounts end
            }
            // Insert event end
            }
        }
        });
        alert()->success('Sync event sunccessfully!', 'Success!');                        
        return redirect()->back();          
    }

    public function createAssessment($assessment) {
        $new_assessment = Assessment::create([
            'guid' => isset($assessment->guid)?$assessment->guid.'_'.time():'',
            'title' => isset($assessment->title)?$assessment->title:'',
            'instructions' => isset($assessment->instructions)?$assessment->instructions:'',
            'cpd_hours' => isset($assessment->cpd_hours)?$assessment->cpd_hours:'',
            'pass_percentage' => isset($assessment->pass_percentage)?$assessment->pass_percentage:'',
            'time_limit_minutes' => isset($assessment->time_limit_minutes)?$assessment->time_limit_minutes:'',
            'maximum_attempts' => isset($assessment->maximum_attempts)?$assessment->maximum_attempts:'',
            'randomize_questions_order' => isset($assessment->randomize_questions_order)?$assessment->randomize_questions_order:'',
        ]);
        return $new_assessment;
    }

    public function updateQuestions($assessment, $new_assessment) {
        
        $questionsIds = collect($assessment->questions)->pluck('guid')->toArray();
        // dd($questionsIds);
        foreach($questionsIds as $key => $questionsId) {
            $questionsIds[$key] = $questionsId.$new_assessment->id;
        }
        // dd($questionsIds);
        $existingQuestions = $new_assessment->questions()->whereIn('guid', $questionsIds)->get()->keyBy('guid');
        
        foreach ($existingQuestions as $existingQuestion) {
            foreach ($assessment->questions as $question) {
                if($question->guid.$new_assessment->id == $existingQuestion->guid) {
                    
                    $collection = collect($question);
                    $update = $collection->only(Question::FILLABLE_FIELDS)->toArray();
                    $update['guid'] = $existingQuestion->guid;
                    $update['description'] = trim($update['description']);
                    $update['description'] = preg_replace('/\\s+/', ' ', $update['description']);
                    $existingQuestion->update($update);

                    //Options
                    if (count($question->options)) {
                        $optionsCollection = collect($question->options)->keyBy('guid');
                        
                        $this->updateQuestionOptions($existingQuestion, $optionsCollection);
                    }
                }
            }
        }
        
        foreach ($assessment->questions as $question) {
            
            if (!$existingQuestions->has($question->guid.$new_assessment->id)) {
                $collection = collect($question);
                $newQuestion = $collection->only(Question::FILLABLE_FIELDS)->toArray();
                $newQuestion['guid'] = $newQuestion['guid'].$new_assessment->id;
                $newQuestion['description'] = trim($newQuestion['description']);
                $newQuestion['description'] = preg_replace('/\\s+/', ' ', $newQuestion['description']);
                
                $createdQuestion = $new_assessment->questions()->create($newQuestion);

                //Options
                if (count($question->options)) {
                    $optionsCollection = collect($question->options)->keyBy('guid');
                    $this->updateQuestionOptions($createdQuestion, $optionsCollection);
                }
            }
        }
    }

    public function updateQuestionOptions($question, $optionsCollection)
    {
        $optionIds = $optionsCollection->pluck('guid')->toArray();
        
        foreach($optionIds as $key => $optionId) {
            $optionIds[$key] = $optionId.$question->id;
        }
        // Existing options
        $existingOptions = $question->options()->whereIn('guid', $optionIds)->get()->keyBy('guid');
        
        foreach ($existingOptions as $existingOption) {
            foreach ($optionsCollection as $option) {
                if($option->guid.$question->id == $existingOption->guid) {
                    $update = collect($option)->only(Option::FILLABLE_FIELDS)->toArray();
                    $update['guid'] = $update['guid'].$question->id;
                    $update['description'] = trim($update['description']);
                    $update['description'] = preg_replace('/\\s+/', ' ', $update['description']);
                    $question->options()->where('guid', $existingOption->guid)->update($update);       
                }
            }
        }
        // dd($question);

        //New options
        foreach ($optionsCollection as $option) {
            
            if (!$existingOptions->has($option->guid.$question->id)) {
                
                $option = collect($option);
                $newOption = $option->only(Option::FILLABLE_FIELDS)->toArray();
                $newOption['guid'] = $newOption['guid'].$question->id;
                $newOption['description'] = trim($newOption['description']);
                $newOption['description'] = preg_replace('/\\s+/', ' ', $newOption['description']);
                $question->options()->create($newOption);
            }
        }
        
        //Delete questions
        // $allOptions = $question->options()->get()->keyBy('guid');
        // $deletedOptionIds = [];
        // foreach ($allOptions as $option) {
        //     if (!$optionsCollection->has($option['guid']))
        //         $deletedOptionIds[] = $option['guid'];
        // }
        // $question->options()->whereIn('guid', $deletedOptionIds)->delete();
    }

    public function addRecordingsToPricing($price, $pricedata) {
        if(count($price->recordings)) {
            foreach($price->recordings as $recording) {
                $video = $recording->video;
                if($video) {
                    $videoCategory = $video->categories;

                    $category_id = null;
                    if($videoCategory) {
                        $category = Category::where('title', $videoCategory->title)->first();
                        
                        if($category) {
                            $category_id = $category->id;
                        } else {
                            $newCategory = Category::create([
                                'title' => $videoCategory->title,
                                'description' => $videoCategory->description
                            ]);
                            $category_id = $newCategory->id;
                        }
                    }

                    $data = [
                        'title' => $video->title,
                        'reference' => $video->reference,
                        'video_provider_id' => $video->video_provider_id,
                        'download_link' => $video->download_link,
                        'width' => $video->width,
                        'height' => $video->height,
                        'can_be_downloaded' => $video->can_be_downloaded,
                        'view_link' => $video->view_link,
                        'category' => $category_id,
                        'hours' => $video->hours,
                        'tag' => $video->tag,
                        'status' => $video->status,
                        'amount' => $video->amount,
                        'description' => $video->meta_description,
                        'view_resource' => $video->view_resource,
                        'width' => '0',
                        'height' => '0',
                        'cover' => (env('APP_THEME') == 'taxfaculty' ? Theme::asset('/img/player.jpg') : "https://imageshack.com/a/img924/9673/A4DN1j.jpg"),
                        'reference_id' => $video->id
                    ];

                    $newVideo = Video::where('reference_id', $video->id)->first();

                    if($newVideo) {
                        $newVideo->update($data);
                    } else {
                        $newVideo = Video::create($data);
                    }
            
                    // $newVideo->features()->sync(! $newVideo['VideoFeaturesList'] ? [] : $newVideo['VideoFeaturesList']);
                    // $newVideo->presenters()->sync(! $newVideo['VideoPresentersList'] ? [] : $newVideo['VideoPresentersList']);

                    $isExist = Recording::where('pricing_id',$pricedata->id)->where('video_id',$newVideo->id)->first();
                    if(!$isExist) {
                        $recording = new Recording();
                        $recording->pricing_id = $pricedata->id;
                        $recording->video_id = $newVideo->id;
                        $recording->save();
                    }                    
                }
            }
        }
    }

    public function createDiscount($discount) {
        $data = [
            'title' => $discount->title,
            'description' => $discount->description,
            'code' => $discount->code,
            'discount_type' => $discount->discount_type,
            'discount_amount' => $discount->discount_amount,
            'has_limited_uses' => $discount->has_limited_uses,
            'remaining_uses' => $discount->remaining_uses
        ];
        $newDiscount = PromoCode::create($data);
        return $newDiscount;
    }
}
