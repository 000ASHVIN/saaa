<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class CustomEftPayments extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'id_number',
        'email',
        'cell',
        'date'
    ];
    protected $table = 'custom_eft_payments';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
