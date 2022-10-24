<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class FailedDebitOrder extends Model
{
    use SearchableTrait;
    protected $guarded = [];
    protected $table = 'failed_debit_order_log';

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
