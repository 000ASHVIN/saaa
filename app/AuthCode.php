<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthCode extends Model
{
    protected $table = 'auth_codes';
    protected $fillable = [
        'title',
        'code',
        'description'
    ];
}
