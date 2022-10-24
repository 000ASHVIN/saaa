<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    protected $guarded = [];
    
    public function importable()
    {
        return $this->morphTo();
    }
}
