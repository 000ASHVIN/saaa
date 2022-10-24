<?php

namespace App\Console\Commands;

use App\Act;
use App\ActList;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class PullActs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:acts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will pull all acts via API';

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
        $client = new Client( ['verify' => false]);

        $auth = [
            'headers' => [
                'Authorization' => 'Bearer wOR9POGfEQWKBp7CzEphAvh43L7lH1XzIGOeUsjsr1dd3O2QLoBGlX4RRrJl'
            ]
        ];

        $response = $client->get('http://www.acts.co.za/api/v1/acts', $auth);
        $data = json_decode($response->getBody()->getContents());


        foreach ($data[0] as $act){
            if (! ActList::where('slug', "{$act->key}/")->exists()){
                $this->info("Storing {$act->key}...");

                $list = ActList::create([
                    'name' => $act->name,
                    'slug' => $act->key."/",
                    'act_year' => $act->act_year,
                    'category' => $act->category
                ]);

                // Retrieve Acts for Act List
                $response = $client->get('http://www.acts.co.za/api/v1/acts/'.$act->key, $auth);
                $data = json_decode($response->getBody());

                // $list->update([
                //     'main_act_id' => $data[0][0]->id
                // ]);

                $this->warn("Fetching Children of {$act->key}...");
                foreach ($data[0] as $item) {
                    Act::create([
                        'id' => $item->id,
                        'parent_id' => $item->parent_id,
                        'act_id' => $list->id,
                        'name' => $item->title,
                        'meta_description' => $item->description,
                        'content' => $item->content,
                        'is_toc_item' => $item->is_toc_item
                    ]);
                }
            }else{
                
                $Acts =  ActList::where('slug',"{$act->key}/")->first();
               

                $response = $client->get('http://www.acts.co.za/api/v1/acts/'.$act->key, $auth);
                $data = json_decode($response->getBody());

                
                foreach ($data[0] as $item) {
                    $Item = Act::where('act_id',$Acts->id)->where('name',$item->title)->first();
                    
                    if(!$Item)
                    {
                        Act::create([
                            'id' => $item->id,
                            'parent_id' => $item->parent_id,
                            'act_id' => $Acts->id,
                            'name' => $item->title,
                            'meta_description' => $item->description,
                            'content' => $item->content,
                            'is_toc_item' => $item->is_toc_item
                        ]);
                        
                    }
                   
                }

            }
        }
    }
}
