<?php

namespace App\Billing;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * @package App\Billing
 */
class Payment extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'amount',
        'date_of_payment',
        'description',
        'method',
        'notes',
        'payment_allocated_by',
        'authKey'
    ];

    /**
     * @var array
     */
    protected $dates = ['date_of_payment'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class)->withTrashed();
    }

    /**
     * @param $date
     * @return static
     */
    public function setDateOfPaymentAttribute($date)
    {
        return $this->attributes['date_of_payment'] = Carbon::parse($date);
    }

    public function PaymentAllocated()
    {
        if ($this->method === 'cc'){
            return 'Allocated by System';
        }
        elseif (!$this->payment_allocated_by){
            return 'Allocated by Staff Member';
        }
        else{
            return 'Allocated by'. ' '. $this->payment_allocated_by;
        }
    }
}