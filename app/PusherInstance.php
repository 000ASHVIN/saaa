<?php
/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 2/13/2017
 * Time: 3:21 PM
 */

namespace App;


use Illuminate\Support\Facades\Config;
use Pusher;

class PusherInstance
{
    public static function sendNotification($message)
    {
        $pusher = self::initializePusher();
        $data['message'] = $message;
        $pusher->trigger('my-channel', 'my-event', $data);
    }

    /**
     * @return Pusher
     */
    public static function initializePusher(): Pusher
    {
        $app_id = Config::get('services.pusher.app_id');
        $app_key = Config::get('services.pusher.public');
        $app_secret = Config::get('services.pusher.secret');
        $pusher = new Pusher($app_key, $app_secret, $app_id);
        return $pusher;
    }
}