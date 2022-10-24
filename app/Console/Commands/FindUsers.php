<?php

namespace App\Console\Commands;

use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use Illuminate\Console\Command;

class FindUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upgrade:free';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will upgrade all the free members!';

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
        $cancelled = Subscription::with('user')->cancelled()->get();
        $plan = Plan::find('45');

        foreach ($cancelled as $cancelledSubscription){
            $this->info('Removing...' .$cancelledSubscription->id);
            $cancelledSubscription->delete();

            if ($cancelledSubscription->user){
                $cancelledSubscription->user->newSubscription('cpd', $plan)->create();
            }
        }

        // Assign Settings to all users.
        $users = User::where('settings', '')->get();

        foreach ($users as $user){
            $user->settings = [];

            if (! $user->settings){
                $this->info('Assigning...');

                $user->settings()->merge([
                    'marketing_emails' => 1,
                    'sms_notifications' => 1,
                    'event_notifications' => 1,
                    'send_invoices_via_email' => 1,
                ]);
            }
        }

        $users = User::doesnthave('subscriptions')->get();

        // Pull a list of users with cancelled subscriptions and then migrate them to free plan.
        $this->info('We have '.count($users).' with no subscription plan');

        foreach ($users as $user){
            $user->newSubscription('cpd', $plan)->create();
        }
    }
}
