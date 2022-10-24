<?php

namespace App\Console\Commands;

use App\Billing\Invoice;
use App\Jobs\PtpReminders;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class NotifyPromiseToPayClients extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ptp:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send a reminder to everyone who has an upcoming promise to pay';

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
        $invoices = collect();
        Invoice::where('ptp_date', '!=', '0000-00-00')->get()->filter(function ($invoice) use($invoices){
            if (Carbon::parse($invoice->ptp_date)->isTomorrow()){
                $invoices->push($invoice);
            }
        });

        $this->info("We have ".count($invoices));
        if (count($invoices)){
            foreach ($invoices as $invoice){
                $job = (new PtpReminders($invoice));
                $this->dispatch($job);
            }
        }
    }
}
