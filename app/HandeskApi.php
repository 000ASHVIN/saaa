<?php

namespace App;

use GuzzleHttp\Client as Guzzle;

/**
* HandeskApi
*/
class HandeskApi
{

    private $client;

    private $url;

    private $token;

	public function __construct()
	{
        $this->client = new Guzzle();
        $this->url = config('services.handesk.url');
        $this->token = config('services.handesk.token');
	}

    /*
    * Create a ticket in handesk and notify user
    */
	public function createAndNotify($requester, $title, $body, $tags, $team_id = null)
	{		

		try {
			$response = $this->client->post($this->url . '/tickets', [
                "headers" => [ "token" => $this->token ],
				'form_params' => [
                    'requester' => $requester,
                    'title' => $title,
                    'body' => $body,
                    'tags' => $tags,
                    'team_id' => $team_id
		    	]
            ]);
            return json_decode($response->getBody())->data->id;
        } 
        catch (\Exception $e) {
	    	return 0;
	    }
	}

    /*
    * Add comment to a ticket and notify user
    */
	public function addComment($ticket, $comment, $user_id=null)
	{
		try {
			$response = $this->client->post($this->url . "/tickets/{$ticket}/comments", [
                "headers" => [ "token" => $this->token ],
				'form_params' => [
                    'body' => $comment,
                    'user' => $user_id
		    	]
            ]);
            return json_decode($response->getBody())->data->id;
        } 
        catch (\Exception $e) {
	    	return 0;
	    }
    }
    
    /*
    * Update ticket status and notify user if solved
    */
    public function updateStatus($ticket, $status)
	{
		try {
			$response = $this->client->put($this->url . "/tickets/".$ticket, [
                "headers" => [ "token" => $this->token ],
				'form_params' => [
                    'status' => $status
		    	]
            ]);
            return json_decode($response->getBody())->data->id;
        } 
        catch (\Exception $e) {
	    	return 0;
	    }
    }

    /*
    * Update ticket
    */
    public function update($ticket, $data)
	{
		try {
			$response = $this->client->put($this->url . "/tickets/".$ticket, [
                "headers" => [ "token" => $this->token ],
				'form_params' => $data
            ]);
            return json_decode($response->getBody())->data->id;
        } 
        catch (\Exception $e) {
	    	return 0;
	    }
    }
    


	
}