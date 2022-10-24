<?php

namespace App\Events;
use App\Events\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InstantEftNotificationReceived extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $payment_key;

    public $success;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($payment_key, $success)
    {
        $this->payment_key = $payment_key;
        $this->success = $success;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['instantefts'];
    }
}
