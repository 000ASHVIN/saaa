<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Repositories\Invoice\SendInvoiceRepository;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRenewableInvoice extends Job implements SelfHandling, ShouldQueue
{
    private $user;
    private $invoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $invoice)
    {
        $this->user = $user;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @param SendInvoiceRepository $sendInvoiceRepository
     * @return void
     */
    public function handle(SendInvoiceRepository $sendInvoiceRepository)
    {
        $user = $this->user;
        $invoice = $this->invoice;
        $sendInvoiceRepository->sendInvoice($user, $invoice);
    }
}
