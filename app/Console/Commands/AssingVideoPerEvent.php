<?php

namespace App\Console\Commands;

use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\Jobs\AssignVideoToUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AssingVideoPerEvent extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:video-per-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will assign videos to tickets per events';

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
        $slugs = collect();

        $events = Event::where('start_date', '>', Carbon::parse('1 January 2018'))
            ->where('is_redirect', false)
            ->where('is_active', true)
            ->has('pricings')
            ->has('pricings.recordings')
            ->get();

        $events->each(function ($event) use($slugs){
            $slugs->push($event->slug);
        });

        foreach ($slugs as $slug){
            $event = Event::findBySlug($slug);
            $pricing = Pricing::has('recordings')->where('event_id', $event->id)->first();
            $video = $pricing->recordings()->first()->video;
            $job = (new AssignVideoToUser($event, $video))->onQueue('default');
            $this->dispatch($job);
        }
    }
}
