<?php

namespace App\Console\Commands;

use App\Subscriptions\Models\Subscription;
use Illuminate\Console\Command;

class UpdateSubscriptionPaymentMethod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:PaymentMethods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update the payment methods for subscriptions';

    /**
     * Create a new command instance.
     *
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
        $debits = collect();
        $subscriptions = Subscription::with('user', 'user.debit')->active()->get();
        foreach ($subscriptions as $subscription){
            if ($subscription->user){
                $user = $subscription->user;
                if ($user->debit != null && $user->debit->has_been_contacted == true){
                    if ($subscription->payment_method == ''){
                        $debits->push($user);
                        $this->info('updating'.' '.$user->first_name.' '.$user->last_name.' '.'subscription payment method to debit order');
                        $subscription->update(['payment_method' => 'debit_order']);
                        $subscription->save();
                    }
                }
            }
        }

    }
}
