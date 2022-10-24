<?php

namespace App\Console\Commands;

use App\Users\User;
use App\Wallet;
use Illuminate\Console\Command;

class AddMissingWallets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallets:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate missing wallets.';

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
        $users = User::doesnthave('wallet')->get();
        foreach ($users as $user){
            $wallet = new Wallet();

            $this->info('saving new wallet for user '.$user->first_name.' '.$user->last_name);
            $user->wallet()->save($wallet);
        }
    }
}
