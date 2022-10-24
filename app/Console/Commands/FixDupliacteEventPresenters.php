<?php

namespace App\Console\Commands;

use App\AppEvents\Event;
use Illuminate\Console\Command;

class FixDupliacteEventPresenters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:event-presenters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will fix the duplicate event presenters';

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
        $events = Event::with('presenters')->get();

        foreach ($events as $event) {
            $presenters = $event->presenters;
            $event->presenters()->sync([]);
            $event->presenters()->sync($presenters);
        }
    }
}
