<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Repositories\Invoice\SendInvoiceRepository;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEventTicketInvoiceJob extends Job implements SelfHandling, ShouldQueue
{
    private $invoice;

    public function __construct($invoice)
    {
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
        $invoice = $this->invoice;
        $sendInvoiceRepository->sendInvoice($invoice->user, $invoice);
    }
}
