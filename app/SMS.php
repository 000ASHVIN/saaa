<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;


class SMS extends Model
{
    protected $table = 'sms';
    protected $fillable = [
        'from',
        'from_name',
        'user_id',
        'to_name',
        'message',
        'number',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
