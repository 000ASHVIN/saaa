<?php

namespace App\Console\Commands;

use App\Subscriptions\Models\Subscription;
use Illuminate\Console\Command;

class assignEventsToPreRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:eventsPregistrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will assignt he relevant events to the pre registrations people.';

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
        $subscriptions = Subscription::with('user')->where('starts_at', 'LIKE', '2016-12-01%')->active();
        dd($subscriptions->count());
    }
}
