<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Video;
use App\Blog\Category;

class TTFWebinarSeriesManual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webinar-series:manual-script-create-series';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $cateories = Category::where('parent_id',0)->get()->pluck('id');
        $subCategories = Category::whereIn('parent_id',$cateories)->get();
        
        foreach($subCategories as $subCategory) {

            $videos = collect();
            
            // Collect videos linked with sub category
            foreach($subCategory->videos as $video) {
                $videos->push($video);
            }

            // Collect videos of linked with  sub sub category
            foreach($subCategory->childCategory() as $topic) {

                foreach($topic->videos as $video) {
                    $videos->push($video);
                }

            }

            // Create series if there are more than 1 videos available
            if(count($videos)>1) {

                $title = trim($subCategory->title.' Series');
                if(Video::where('title', $title)->get()->count()) {
                    $this->info("Series already exist with same name please check: <".$title.">\n");
                }
                else {

                    $series_data = [];
                    $series_data['title'] = $title;
                    $series_data['description'] = $subCategory->title.' Series';
                    $series_data['discount'] = 10;
                    $series_data['type'] = 'series';
                    $series_data['tag'] = 'studio';

                    $webinar_series = Video::create($series_data);
                    if($webinar_series) {
                        $series_videos = [];
                        foreach($videos as $key=>$video) {
                            $series_videos[$video->id] = ['sequence' => $key+1];
                        }
                        $webinar_series->webinars()->attach($series_videos);
                        $this->info("Series created: <".$title."> with ".count($videos)." videos\n");
                    }
                    else {
                        $this->info("Failed to create series: <".$title.">\n");
                    }
                }

            }
        }
    }
}
