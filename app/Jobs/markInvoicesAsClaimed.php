<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class markInvoicesAsClaimed extends Job implements SelfHandling, ShouldQueue
{
    private $invoices;
    private $option;

    /**
     * Create a new job instance.
     *
     * @param $invoices
     * @param $option
     */
    public function __construct($invoices, $option)
    {
        $this->invoices = $invoices;
        $this->option = $option;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->invoices as $invoice){
            if ($invoice->note){
                $invoice->note->update(['commision_claimed' => $this->option]);
                $invoice->note->save();
            }
        }
    }
}
