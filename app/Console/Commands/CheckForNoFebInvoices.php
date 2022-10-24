<?php

namespace App\Console\Commands;

use App\Subscriptions\Models\Subscription;
use Illuminate\Console\Command;

class CheckForNoFebInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'no:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will give us all the people who does not have a feb monthly invoice.';

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
        $subscriptions = Subscription::all()->filter(function ($subscriber){
            if ($subscriber->plan->interval == 'month'){
                return $subscriber;
            }
        });

        foreach ($subscriptions as $subscription){
            dd(count($subscription->user->invoices->where('type', 'subscription')));
        }
    }
}
