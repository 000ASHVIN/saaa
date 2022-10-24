<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceOrderPayment extends Model
{
    protected $table = 'invoice_order_payments';

    protected $fillable = [
        'invoice_id',
        'amount',
        'date_of_payment',
        'description',
        'method',
        'notes',
        'tags',
    ];

    protected $dates = ['date_of_payment'];

    public function invoice_order()
    {
        return $this->belongsTo(InvoiceOrder::class)->withTrashed();
    }

    public function getAmountAttribute()
    {
        return $this->attributes['amount'];
    }
}
