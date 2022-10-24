<?php

namespace App\Billing;

use App\InvoiceOrder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * @package App\Billing
 */
class Item extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'description',
        'price',
        'quantity',
        'discount',
        'item_id',
        'item_type',
        'extra_details',
        'course_type'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($item) {
            if (count(request()->all())>0)
            {   
                $item->extra_details = json_encode(request()->all()) ;
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'items_lists');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function invoiceOrders()
    {
        return $this->belongsToMany(InvoiceOrder::class);
    }
    public function productable(){
        return $this->morphTo('item');   
    }
}
