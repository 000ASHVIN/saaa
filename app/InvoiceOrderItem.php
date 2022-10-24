<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceOrderItem extends Model
{
    protected $table = 'invoice_order_items';
    protected $fillable = [
        'type',
        'name',
        'description',
        'price',
        'quantity',
        'discount'
    ];

    public function invoiceOrders()
    {
        return $this->belongsToMany(InvoiceOrder::class);
    }
}
