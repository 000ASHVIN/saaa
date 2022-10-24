<?php

namespace App\Console\Commands;
error_reporting(0);

use App\AppEvents\Venue;
use App\Billing\Invoice;
use App\Users\User;
use App\Video;
use Illuminate\Console\Command;

class assignWebinarVideosToClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:purchasedWebinar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We will assign all purchased webinars to all clients';

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
        $this->doAwesomeStoreStuff();
        $this->info('Ending with store recordings');
        $this->doAwesomeEventStuff();
    }

    public function doAwesomeEventStuff()
    {
        $venues = Venue::with('tickets', 'tickets.invoice')->where('type', 'online')->get();
        /*
         * loop through the venues and get the tickets with invoices.
         */

        $venues->each(function ($venue){
            foreach ($venue->tickets as $ticket){
                if (! $ticket->invoice || $ticket->invoice && $ticket->invoice->status == 'paid'){
                    foreach ($ticket->pricing->recordings as $recording) {
                        if (! $ticket->user->webinars->contains($recording->video)){

                            $this->info('Saving video for '.$ticket->user->email);
                            $ticket->user->webinars()->save($recording->video);
                        }else{

                            $this->info('Skipping '.$ticket->user->email);
                            continue;
                        }
                    }
                }else{
                    continue;
                }
            }
        });
    }

    public function doAwesomeStoreStuff()
    {
        $this->info('Starting with the online store');
        $invoices = Invoice::where('type', 'store')->where('status', 'paid')->get();

        foreach ($invoices as $invoice){
            $invoice->orders->each(function ($order) use($invoice){
                $links = $order->product->links()->where('name', 'Recording')->get();
                if (count($links)){
                    foreach ($links as $link){
                        $video = Video::where('download_link', $link->url)->first();
                        if (isset($video) && ! $invoice->user->webinars->contains($video)){

                            $this->info('assigning video recording to '.$invoice->user->email);
                            $invoice->user->webinars()->save($video);
                        }
                    }
                }
            });
        }
    }
}
