<?php

namespace App\Console\Commands;

use Config;
use App\Video;
use Carbon\Carbon;
use App\Users\User;
use App\AppEvents\Ticket;
use App\AppEvents\Pricing;
use Illuminate\Console\Command;
use App\Subscriptions\Models\Plan;
use Illuminate\Support\Facades\DB;
use App\Subscriptions\Models\Feature;
use App\Subscriptions\Models\Subscription;

class GenerateFreeVideoTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:free-video-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will assign all the Subscription events to the subscribes';
    protected $users = [] ;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
       
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //ini_set('memory_limit', '-1');
        $users = User::with('webinars')->select('users.*','plans.name',
        DB::raw('videos.id As Video_id'),
        DB::raw('users.id As User_id'),
        DB::raw('count(videos.id) As count'))
        ->where('users.status', 'active')
        ->leftJoin('subscriptions', 'subscriptions.user_id', '=', 'users.id')
        ->leftJoin('plans', 'plans.id', '=', 'subscriptions.plan_id')
        ->leftJoin('feature_plan', 'feature_plan.plan_id', '=', 'plans.id')
        ->leftJoin('feature_video', 'feature_plan.feature_id', '=', 'feature_video.feature_id')
        ->leftJoin('videos', 'videos.id', '=', 'feature_video.video_id')
        ->leftJoin('webinar_users', function($join){
            $join->on('webinar_users.user_id', '=', 'users.id');
            $join->on('webinar_users.video_id','=','videos.id'); 
        })
        ->where('subscriptions.name','cpd')
        ->whereNull('subscriptions.canceled_at')
        ->whereNull('webinar_users.id')
        ->where('subscriptions.ends_at','>=',Carbon::now())
        ->where('subscriptions.suspended',false)
        ->where('subscriptions.deleted_at', null)
        ->where('subscriptions.plan_id', '=', 45)
        ->groupBy('feature_video.video_id')
        ->groupBy('users.id')
        ->having('count', '>' , 0)
        ->get();
      
        $vid = $users->unique('Video_id')->pluck('Video_id');
        $Allvideo = Video::whereIn('id',$vid)->get();
       
        foreach($users as $user)
        {
            if ($user->subscription('cpd')->active()) {
                $Getvideo = $Allvideo->where('id', $user->Video_id)->first();
                if ($Getvideo) {
                    if (!in_array($user->Video_id, $user->webinars->pluck('id')->toArray())) {
                       // $cpd = $this->assignCPDHours($user, $Getvideo->hours, 'Webinars On Demand - '.$Getvideo->title, null, $Getvideo->category, false);
                      //  $this->assignCertificate($cpd, $Getvideo);
                        $this->assignWebinarOnDemand($user, $Getvideo);
                    }
                 }
            }
        } 
      
    }

    public function assignWebinarOnDemand($user, $video) {

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
        }
        else {
            $allVideos->push($video);
            $user->webinars()->save($video);
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

   
}
