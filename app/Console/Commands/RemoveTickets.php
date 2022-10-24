<?php

namespace App\Console\Commands;

use App\Wallet;
use App\Users\User;
use Illuminate\Console\Command;
use App\AppEvents\EventRepository;

class RemoveTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:tickets {event} {venue?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate remove tickets from event.';

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
    public function handle(EventRepository $EventRepository)
    {
        //$EventRepository= New EventRepository();
        $event = $this->argument('event');
        $venue = $this->argument('venue');
        $EventGet = $EventRepository->findEvent($event);
        if($EventGet)
        {
            $tickets = $EventGet->tickets;
            foreach ($tickets as $ticket){
                $ticket->delete();
            }
            $this->info('Done '.count($tickets));
        }else{
            $this->info('No Event Found');
        }
    }
}
