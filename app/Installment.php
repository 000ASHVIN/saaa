<?php

namespace App;

use App\Billing\Invoice;
use App\Subscriptions\Subscription;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $dates = ['created_at', 'updated_at', 'due_date'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
