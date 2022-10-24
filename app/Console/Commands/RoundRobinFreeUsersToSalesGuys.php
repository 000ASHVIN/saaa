<?php

namespace App\Console\Commands;

use App\Jobs\NotifySalesAgentOfFreeUser;
use App\Rep;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class RoundRobinFreeUsersToSalesGuys extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'free:round-robin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send an email to the sales guys for the free members.';

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
        $shouldSend = collect();
        User::all()->each(function ($user) use($shouldSend){
            if($user->round_include() == true){
                $shouldSend->push($user);
            }
        });

        $this->info("We have ".count($shouldSend).' Free members that will participate');

        foreach ($shouldSend as $user){
            $rep = Rep::nextAvailable();
            $this->dispatch((new NotifySalesAgentOfFreeUser($rep, $user)));
            $user->update([
                'round_robin_notified' => true,
                'round_robin_notified_date' => Carbon::now()
            ]);
            $rep->update(['emailedLast' => Carbon::now()]);
        }
    }
}
