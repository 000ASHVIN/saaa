<?php

namespace App\Console\Commands;

use App\AppEvents\Ticket;
use Illuminate\Console\Command;

class AssignInvoiceIdToTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:invoiceId-to-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $tickets = Ticket::has('invoice_order')->where('invoice_id', '')->get();
        foreach ($tickets as $ticket){
            if ($ticket->invoice_order){
                if ($ticket->invoice_order->invoice){
                    $this->info('Fixing Ticket '.$ticket->id);
                    $ticket->update(['invoice_id' => $ticket->invoice_order->invoice->id]);
                    continue;
                }
                continue;
            }
        }
    }
}
