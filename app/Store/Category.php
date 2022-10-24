<?php

namespace App\Store;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'store_categories';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}
