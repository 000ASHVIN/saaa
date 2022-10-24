<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'data' => 'array',
        'imported' => 'array',
        'existing' => 'array',
        'invalid' => 'array',
        'duplicates' => 'array'
    ];

    public function provider()
    {
        return $this->belongsTo(ImportProvider::class);
    }

    public function batches()
    {
        return $this->hasMany(ImportBatch::class);
    }
}
