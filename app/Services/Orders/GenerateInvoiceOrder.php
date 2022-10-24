<?php namespace App\Services\Orders;
use App\InvoiceOrder;
/**
 * Interface GenerateInvoice
 * @package App\Services\Invoicing
 */
interface GenerateInvoiceOrder {
    public function generate(InvoiceOrder $order);
}