<?php

namespace App\Console\Commands;

use App\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;

class updateVideosTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sluggify:videos';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'This command Will save sluggify all videos';

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
        $videos = Video::all();
        foreach ($videos as $video){
            $video->slug = null;
            if (count($video->pricings())){
                $description = ($video->event ? $video->event->short_description : "None");
                $video->description = $description;
            }
            $video->price = '399';
            $video->hours = Carbon::parse($video->event->start_time)->diffInRealMinutes(Carbon::parse($video->event->end_time)) / 60;
            $video->update();
            $video->save();
            $this->info('Just Fixed '.$video->title.' and the description is '.$video->description);
        }
    }
}
