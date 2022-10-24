<?php

namespace App\Jobs;

use App\Events\Event;
use App\Video;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AssignVideoToUser extends Job implements SelfHandling, ShouldQueue
{
    use DispatchesJobs;
    private $event;
    private $video;

    /**
     * Create a new job instance.
     *
     * @param Event $event
     * @param Video $video
     */
    public function __construct($event, Video $video)
    {
        $this->event = $event;
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $video = $this->video;
        $this->event->tickets->each(function ($ticket) use($video){
            $job = (new assignVideoToUserJob($ticket, $video));
            $this->dispatch($job);
        });
    }
}
