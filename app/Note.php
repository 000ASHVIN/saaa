<?php

namespace App;

use App\Billing\Invoice;
use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'user_notes';
    protected $guarded = [];

    protected $appends = ['event'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function upgrade()
    {
        return $this->hasOne(UpgradeSubscription::class);
    }

    public function order()
    {
        return $this->hasOne(InvoiceOrder::class);
    }

    public function getEventAttribute()
    {
        return ($this->order && $this->order->ticket ? $this->order->ticket->event->name : ($this->invoice && $this->invoice->ticket ? $this->invoice->ticket->event->name : ""));
    }
}
