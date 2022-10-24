<?php namespace App\Services\Invoicing;

use App\Events\Invoice;

/**
 * Interface GenerateInvoice
 * @package App\Services\Invoicing
 */
interface GenerateInvoice {

    /**
     * @param Invoice $invoice
     * @return mixed
     */
    public function generate(Invoice $invoice);

}