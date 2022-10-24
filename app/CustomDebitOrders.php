<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class CustomDebitOrders extends Model
{
    protected $guarded = [];
    protected $table = 'custom_debit_orders';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
