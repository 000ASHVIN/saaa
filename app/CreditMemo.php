<?php

namespace App;

use App\Billing\Invoice;
use Illuminate\Database\Eloquent\Model;

class CreditMemo extends Model
{
    protected $fillable = [
        'tags',
        'amount',
        'user_id',
        'reference',
        'invoice_id',
        'description',
        'category',
        'transaction_date'
    ];

    protected $table = 'credit_memos';

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
