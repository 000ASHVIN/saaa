<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatabaseQuestionnaire extends Model
{
    protected $casts = [
        'accounting_field' => 'array',
        'reduce_risk_products' => 'array',
    ];

    protected $table = 'database_questionnaires';
    protected $guarded = [];
}
