<?php

namespace App\Console\Commands;

use App\AppEvents\Event;
use App\AppEvents\Ticket;
use Illuminate\Console\Command;

class RemoveDuplicateTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will remove duplicate tickets.';

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
        $event = Event::with('tickets')->find(45);

        $no_invoices = collect();
        $this->info('We have '.count($no_invoices).' '.'tickets with no invoices');
        foreach ($event->tickets as $ticket){
            if($ticket->venue_id == '119'){
                if ($ticket->invoice_id == null && $ticket->venue_id == '119'){
                    $no_invoices->push($ticket);
                }
            }
        }

        $duplicates = $no_invoices->groupBy('user_id');
        foreach ($duplicates as $tickets){
            if (count($tickets) == 5){
                $tickets->shift();
                foreach ($tickets as $ticket){
                    $this->info('We are removing'.' '.$ticket->id.' '.$ticket->description.' '.'for '.$ticket->user_id);
                    $ticket->delete();
                }
            }
        }

        $this->info('done');
    }
}
