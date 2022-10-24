<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class assignInvoiceIdToSubscription extends Job implements SelfHandling, ShouldQueue
{
    private $user;
    private $invoice;

    public function __construct($user, $invoice)
    {
        $this->user = $user;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $invoice = $this->invoice;
        $user->subscription('cpd')->setInvoiceId($invoice);
    }
}
