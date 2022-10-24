<?php

namespace App\Console\Commands;

use App\Users\User;
use App\Wallet;
use Illuminate\Console\Command;

class CreateWallets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:wallets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create a wallet for everyone to use.';

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
        $users = User::all();
        foreach ($users as $user){
            if (count($user->wallet) <= 0){
                $this->info('Assigning Wallet for '.$user->first_name.' '.$user->last_name);
                $wallet = new Wallet();
                $user->wallet()->save($wallet);
            }
        }
    }
}
