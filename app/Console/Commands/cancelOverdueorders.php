<?php

namespace App\Console\Commands;

use App\InvoiceOrder;
use App\Jobs\SendOrderOverdueReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class cancelOverdueorders extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:unpaid_orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $sevenDays = collect();
        $expired = collect();

        $orders = InvoiceOrder::where('status', 'unpaid')->get();
        $expireDate = Carbon::now()->addDays(30)->endOfDay();

        foreach ($orders as $order){
            if ($order->expired_at){
                if ($order->expired_at <= $expireDate){
                    if ($order->expired_at->diffInDays($expireDate) == 0){
                        $expired->push($order);
                        $this->cancelorder($order);
                        continue;

                    }elseif($order->expired_at->diffInDays($expireDate) == 7){
                        $sevenDays->push($order);
                        $this->sendReminder(7, $order);
                        continue;
                    }
                }
            }
        }
    }

    public function cancelorder($order)
    {
        $this->info('Cancelling Order #'.$order->reference);
    }

    public function sendReminder($day, $order)
    {
        $this->info('We are sending a reminder for unpaid order day '.$day);
        $this->dispatch((new SendOrderOverdueReminder($day, $order)));
    }
}
