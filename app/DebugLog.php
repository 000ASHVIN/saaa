<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DebugLog extends Model
{
    protected $fillable = ['body','data'];
    protected $casts = [
        'data' => 'array'
    ];

    public static function log($text, $data = [])
    {
        static::create(['body' => $text, 'data' => $data]);
    }
}
