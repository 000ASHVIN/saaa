<?php

namespace App\Billing;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * @package App\Billing
 */
class Creditnote extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
    	'amount',
    	'date_of_credit_note',
    	'description',
    	'notes',
    ];

    /**
     * @var array
     */
    protected $dates = ['date_of_credit_note'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
    	return $this->belongsTo(Invoice::class);
    }

    /**
     * @param $date
     * @return static
     */
    public function setDateOfPaymentAttribute($date)
    {
    	return $this->attributes['date_of_credit_note'] = Carbon::parse($date);
    }
}
