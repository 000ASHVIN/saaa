<?php

namespace App;

use GuzzleHttp\Client;

class SyncEvent
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('EVENT_API'),
        ]);
    }

    function sync_event($event) {
        $event = $event->fresh();
        if($event->is_synced_event) {
            $event_name_old = $event->name;
    
            //sync event with ttf
            $response = $this->client->get('api/event/'.url_encode($event_name_old).'/sync/'.$event->id);
            $syncTaxFacultyEvent = json_decode($response->getBody()->getContents());
    
            //get event data from ttf
            $getEvent = $this->client->get('api/event/'.$event->id.'/reference_id');
            $event_data = json_decode($getEvent->getBody()->getContents());
    
            if(count($event_data) > 0) {
                $event->is_synced_event = true;
                $event->save();
            }
        }
    }

}