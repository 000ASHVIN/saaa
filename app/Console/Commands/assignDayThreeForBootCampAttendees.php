<?php

namespace App\Console\Commands;

use App\AppEvents\Date;
use App\AppEvents\Ticket;
use Illuminate\Console\Command;

class assignDayThreeForBootCampAttendees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'add:dayThreeForBootcamp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will assign the next day to the bootcamp attendees';

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
        $date = Date::find(387);
        $tickets = Ticket::where('event_id', '170')->get();

        foreach ($tickets as $ticket){
            $this->info('Saving Ticket');
            $ticket->dates()->save($date);
        }
    }
}
