<?php

namespace App\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkInvoicesAsRefunded extends Job implements SelfHandling, ShouldQueue
{
    public $invoices;

    /**
     * Create a new job instance.
     *
     * @param $invoices
     */
    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invoices = $this->invoices;
        foreach ($invoices as $invoice) {
            $invoice->refund = true;
            $invoice->save();
        }
    }
}
