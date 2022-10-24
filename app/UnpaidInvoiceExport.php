<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnpaidInvoiceExport extends Model
{
    protected $table = 'unpaid_invoices_export';
    protected $guarded = [];
}
