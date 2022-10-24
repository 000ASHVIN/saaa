<?php

namespace App\Console\Commands;

use App\CustomDebitOrders;
use App\DebitOrder;
use Illuminate\Console\Command;

class UpdateUserDebitOrderDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:debit_order_details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will command will synch debit order details on client accounts';

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
        $debits = CustomDebitOrders::with('user', 'user.debit')->get();

        foreach ($debits as $debit){

            // Set the user
            $user = $debit->user;

           if ($user->debit()->count() == 0){
               $this->info('Creating '.$user->first_name.' '.$user->last_name.' debit order details on profile');
                $debit_detail = new DebitOrder([
                    'bank' => $debit->bank,
                    'number' => $debit->number,
                    'type' => $debit->type,
                    'branch_name' => $debit->branch_name,
                    'branch_code' => $debit->branch_code,
                    'billable_date' => $debit->billable_date,
                    'has_been_contacted' => true
                ]);
                $user->debit()->save($debit_detail);

           }elseif ($user->debit()->count() != 0){
               $this->info('Updating '.$user->first_name.' '.$user->last_name.' debit order details on profile');
                $user->debit->update([
                    'bank' => $debit->bank,
                    'number' => $debit->number,
                    'type' => $debit->type,
                    'branch_name' => $debit->branch_name,
                    'branch_code' => $debit->branch_code,
                    'billable_date' => $debit->billable_date,
                    'has_been_contacted' => true
                ]);
           }
        }
    }
}
