<?php

namespace App\Console\Commands;

use App\Subscriptions\Models\Subscription;
use App\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AssignVideosToActiveCpdSubscriber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:assign_videos_to_active_cpd_subscriber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'assign some specified videos to CPD subscriber';

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
        // $videos = Video::whereIn('id',[440,598,646,604,653,599,350,586])->get();
        $videos = Video::whereIn('id',[60,61,62,64])->get();
        $subscriptions = Subscription::with('user')->where('name','cpd')->where(function ($query) {
            return $query->where('ends_at','>=',Carbon::now())
            ->orWhere(function ($query) {
                return $query->where('trial_ends_at', '>', Carbon::now())
                      ->whereNotNull('trial_ends_at');
            });
        })->groupBy('user_id')->get();
        foreach($subscriptions as $subscription)
        {
            $user = $subscription->user;
            foreach($videos as $video)
            {
                $owned_webinars = $user->webinars->pluck('id')->toArray();
                if(!in_array($video->id,$owned_webinars))
                {
                    $user->webinars()->save($video);
                    $this->info("Assigned video to  : ".$user->first_name.' '.$user->last_name);
                }  
            }
        }
    }
}
