<?php

namespace App\Console\Commands;

use App\DebitOrder;
use Illuminate\Console\Command;

class copyAccountHolderNameAndIdNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coppy:debit_details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will copy all exiting account holder info to debit orders';

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
        $debit_orders = DebitOrder::has('user')->get();
        foreach ($debit_orders as $debit_order){

            $this->info("We are fixing ".$debit_order->user->full_name());
            if ($debit_order->account_holder == ''){
                $debit_order->update(['account_holder' => $debit_order->user->full_name()]);
            }

            if ($debit_order->id_number == ''){
                $debit_order->update(['id_number' => $debit_order->user->id_number]);
            }
        }
    }
}
