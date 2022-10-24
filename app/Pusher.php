<?php

namespace App;

use Pusher as PusherModule;
/**
 * Pusher.
 */
class Pusher
{
    protected $pusher;
    public function __construct()
    {
        $this->pusher = new PusherModule(env('PUSHER_KEY'), env('PUSHER_SECRET'), env('PUSHER_APP_ID'), ['cluster' => env('PUSHER_APP_CLUSTER', 'ap2')]);
    }

    public function sendMessage($room, $message)
    {
        try {
            $this->pusher->trigger('chat', 'room_'.$room->id, ['message' => $message]);
        } catch (\Exception $e) {
            return json_decode($e->getMessage(), true);
        }
    }
}
