<?php

namespace App\Console\Commands;

use App\AppEvents\Event;
use Illuminate\Console\Command;

class FixDupliacteEventVenues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:event-venues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will fix the duplicate event venues';

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
        $events = Event::with('venues')->get();
        foreach ($events as $event) {
            $venues = $event->venues;
            $event->venues()->sync([]);
            $event->venues()->sync($venues);
        }
    }
}
