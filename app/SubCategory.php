<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = [];

    public function faqs()
    {
        return $this->belongsToMany(FaqQuestion::class);
    }
}
