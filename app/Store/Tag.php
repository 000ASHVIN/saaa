<?php

namespace App\Store;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'store_tags';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'store_product_tag');
    }
}
