<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThreeDSecureCompleted extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $md;
    public $pares;
    public $time;

    /**
     * Create a new event instance.
     *
     * @param $md
     * @param $pares
     */ 
    public function __construct($md, $pares, $time)
    {
        $this->md = $md;
        $this->pares = $pares;
        $this->time = $time;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['3ds'];
    }
}
