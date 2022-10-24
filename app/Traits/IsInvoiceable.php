<?php

namespace App\Traits;

use App\Billing\Item;

trait IsInvoiceable
{
    /**
     * @return Item
     */
    public function createInvoiceItem()
    {
        return Item::create($this->toInvoiceItemArray());
    }

    /**
     * @return array
     */
    public function toInvoiceItemArray()
    {
        return [
            'type' => $this->getType(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'discount' => $this->getDiscount()
        ];
    }

}