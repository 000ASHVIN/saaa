<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreCPD extends Model
{
    protected $table = 'pre_cpds';
    protected $guarded = [];

    protected $casts = [
        'events' => 'json'
    ];
}
