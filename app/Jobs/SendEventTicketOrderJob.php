<?php

namespace App\Jobs;
use App\Repositories\InvoiceOrder\SendInvoiceOrderRepository;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEventTicketOrderJob extends Job implements SelfHandling, ShouldQueue
{
    private $order;
    /**
     * Create a new job instance.
     *
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @param SendInvoiceOrderRepository $sendInvoiceOrderRepository
     * @return void
     */
    public function handle(SendInvoiceOrderRepository $sendInvoiceOrderRepository)
    {
        $order = $this->order;
        $sendInvoiceOrderRepository->sendInvoice($order->user, $order);
    }
}
