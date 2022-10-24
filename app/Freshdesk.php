<?php
namespace App;
use GuzzleHttp\Client;

class Freshdesk
{
    /* Connection to Freshdesk */
    public static function connect($method){
        $client = new Client();
        return $client->get(env('FRESHDESK_URL').$method, ['auth' => [env('FRESHDESK_U'), '']]);
    }

    /* Post to Freshdesk */
    public static function post_connect($url, $data){

        $client = new Client();
        return $response = $client->post(env('FRESHDESK_URL').$url,
            [
                'auth' => [env('FRESHDESK_U'), env('')],
                'headers' => ['content-Type'=> 'application/json'],
                'json' => $data,
            ]
        );
    }

    /* List All Tickets */
    public static function list_tickets(){
        return json_decode(Freshdesk::connect('/tickets')->getBody()->getContents());
    }

    /* List All Tickets */
    public static function show_ticket($id){
        return json_decode(Freshdesk::connect('/tickets/'.$id)->getBody()->getContents());
    }

    /* List ticket Replies */
    public static function list_ticket_replies($ticketId)
    {
        return json_decode(Freshdesk::connect('/tickets/'.$ticketId.'/conversations')->getBody()->getContents());
    }

    /* List All Tickets */
    public static function list_tickets_filter($query){
        return json_decode(Freshdesk::connect('/search/tickets?query="'.$query.'"')->getBody()->getContents());
    }

    /*
     * Create a new Ticket
     */
    public static function create_ticket($data){
        return Freshdesk::post_connect('/tickets', $data);
    }

    /*
     * Create a new Ticket Reply
     */
    public static function create_ticket_reply($ticketId, $data){
        return Freshdesk::post_connect('tickets/'.$ticketId.'/reply', $data);
    }

    /* List all Solution Categories */
    public static function list_solution_categories(){
        return json_decode(Freshdesk::connect('solutions/categories')->getBody()->getContents());
    }

    /* List all Solution Folders in a Category */
    public static function list_category_solutions($categoryId){
        return json_decode(Freshdesk::connect('solutions/categories/'.$categoryId.'/folders')->getBody()->getContents());
    }

    /* ist all Solution Articles in a Folder */
    public static function list_articles_in_folder($folderid, $query=''){
        return json_decode(Freshdesk::connect('solutions/folders/'.$folderid.'/articles?'.$query)->getBody()->getContents());
    }
}