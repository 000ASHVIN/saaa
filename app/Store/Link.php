<?php

namespace App\Store;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'store_links';
    protected $dates = ['created_at','updated_at'];
    protected $guarded = ['id','product_id','created_at','updated_at'];
    protected $fillable = ['url', 'name', 'instructions', 'secret'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
