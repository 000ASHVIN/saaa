<?php

namespace App\Console\Commands;

use App\Subscriptions\Models\Subscription;
use App\Users\User;
use Illuminate\Console\Command;

class cleanSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will clean all duplicate and cancelled subscriptions.';
    /**
     * @var Subscription
     */
    private $subscription;

    /**
     * Create a new command instance.
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription)
    {
        parent::__construct();
        $this->subscription = $subscription;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $duplicates = collect();
        $users = User::with('subscriptions')->get();
        foreach ($users as $user){
            if ($user->subscriptions->count() >= 2){
                $duplicates->push($user);
            }
        }

        $this->info('we have '.count($duplicates).' '.'users with duplicate subscriptions');
        foreach ($duplicates as $duplicate){
            foreach ($duplicate->subscriptions as $subscription){
                if ($subscription->status == 'canceled') {
                    $this->info('We are deleting canceled subscription for '.$user->fresh()->first_name);
                    $subscription->delete();
                }
            }
        }


        if (count($duplicates) == 0){
            $this->info('Success! we have removed all duplicate subscriptions');
        }else{
            $this->info('We still have '.count($duplicates).' '.'duplicate subscriptions');
        }

    }
}
