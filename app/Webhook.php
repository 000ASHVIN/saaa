<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function scopeSource($query, $source)
    {
        return $query->where('source', $source);
    }
}
