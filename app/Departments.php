<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $table = "departments";
    protected $fillable = [
        'title',
        'description'
    ];

    public function jobs()
    {
        return $this->hasMany(Jobs::class);
    }
}
