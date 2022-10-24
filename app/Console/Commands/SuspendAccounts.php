<?php

namespace App\Console\Commands;

use App\Users\User;
use App\Users\UserRepository;
use Illuminate\Console\Command;

class SuspendAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suspend:user-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will suspend everyone that is already suspended';
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create a new command instance.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
        * Step 1
        * Grab all the users that is currently suspended where their payment method is NOT debit Order.
        */
        $users = User::where('status', 'suspended')
            ->where('suspended_at', null)
            ->where('payment_method', '!=', 'debit_order')
            ->get();

        /*
         * Step 2
         * Filter these users by checking their overdue invoices and if their overdueInvoices is greater than 2, We need these people..
         */
        $ShouldSuspend = $users->filter(function ($user){
           if ($user->overdueInvoices()->where('type', 'subscription')->count() > 2){
               return $user;
           }
        });

        /*
         * Step 3
         * Give me feedback we need to know how many people we have that is not paying us.
         */
        $this->info('We have '.count($ShouldSuspend));
        die();

        /*
         * Step 4
         * This is the part where we would be
         * Setting the suspended_at at date to today.
         * Cancelling their CPD subscription package.
         * Sending them an email.
         * Our CPD Subscription figures will drop with the amount that we receive from above as their subscriptions would be cancelled.
         */
        foreach ($users as $user){
            $this->info('We are suspending '.$user->first_name.' '.$user->last_name);
            $this->userRepository->suspend($user);
        }
    }
}
