<?php

namespace App\Console\Commands;

use App\Billing\Invoice;
use Illuminate\Console\Command;

class UpdateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates all the invoices that has been marked as paid to set the status to paid';

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
        $invoices = Invoice::all();

        foreach ($invoices as $invoice) {
            if ($invoice->paid) {
                $invoice->status = 'paid';
                $invoice->save();
            } else if (! $invoice->paid) {
                $invoice->status = 'unpaid';
                $invoice->save();
            }
        }
    }
}
