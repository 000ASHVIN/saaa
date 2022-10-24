<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $table = 'wallet_transactions';
    protected $guarded = [];

    public function getAmountAttribute()
    {
        return $this->attributes['amount'] / 100;
    }

    /**
     * Set the amount attribute
     * @param $amount
     * @return
     */
    public function setAmountAttribute($amount)
    {
        return $this->attributes['amount'] = $amount * 100;
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
