<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class assignVideoToUserJob extends Job implements SelfHandling, ShouldQueue
{
    private $ticket;
    private $video;

    public function __construct($ticket, $video)
    {
        $this->ticket = $ticket;
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->ticket->user;
        if ($this->ticket->user && ! $this->ticket->invoice){
            if (! $user->webinars->contains($this->video)){
                $user->webinars()->save($this->video);
            };

        }elseif($this->ticket->user && $this->ticket->invoice && $this->ticket->invoice->status == 'paid'){
            if (! $user->webinars->contains($this->video)){
                $user->webinars()->save($this->video);
            };
        }
    }
}
