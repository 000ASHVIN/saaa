<?php

namespace App;

use App\Models\Course;
use App\Store\Product;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $dates = ['created_at','updated_at'];
    protected $guarded = ['id','created_at','updated_at'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
