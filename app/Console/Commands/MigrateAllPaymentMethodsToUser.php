<?php

namespace App\Console\Commands;

use App\Subscriptions\Models\Subscription;
use Illuminate\Console\Command;

class MigrateAllPaymentMethodsToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:paymentMethods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will coppy all payment methods to the actual users.';

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
        $subscribers = Subscription::with('user')->get();
        $this->info('We have '.count($subscribers));

        foreach ($subscribers as $subscriber){
            if ($subscriber->payment_method){
                if ($subscriber->payment_method != $subscriber->user->payment_method){
                    $this->info('We are updating '.$subscriber->user->first_name);
                    $subscriber->user->update(['payment_method' => $subscriber->payment_method]);
                }
            }
        }
    }
}
