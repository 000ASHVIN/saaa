<?php

namespace App\Console\Commands;

use App\AppEvents\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDuplicateDateTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:ticket-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will remove all duplicated date tickets';

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
        $duplicates = DB::table('date_ticket')
            ->select('date_id', 'ticket_id')
            ->groupBy('date_id', 'ticket_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate){
            $ticket = Ticket::withTrashed()->find($duplicate->ticket_id);
            if ($ticket->dates){
                $correct = $ticket->dates->unique();
                $ticket->dates()->sync([]);
                $ticket->dates()->sync($correct);
            }else{
                $this->info($duplicate->ticket_id);
            }
        }
    }
}
