<?php

namespace App\Billing;

use App\Billing\Invoice;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
	use SoftDeletes;

	protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            
            if($transaction->type == 'credit')
            {
            	if($transaction->invoice)
            	{
            		// Update Invoice Balance
            		if($transaction->invoice->balance > 0)
            		{
            			$transaction->invoice->update([
	            			'balance' => $transaction->invoice->balance - $transaction->amount
	        			]);
            		}
            	}
            }
        });
    }

	/**
	 * Dates to be converted to Carbon instances
	 * 
	 * @var array
	 */
	protected $dates = ['deleted_at', 'date'];

	/**
	 * Which fields must be protected
	 * 
	 * @var array
	 */
    protected $guarded = [];

    /**
     * A Transaction belongs to a User
     * 
     * @return User The user the transaction belongs to
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    /**
     * A Transaction belongs to an Invoice
     * 
     * @return Invoice Invoice the transaction belongs to
     */
    public function invoice()
    {
    	return $this->belongsTo(Invoice::class);
    }

    public function totalCredit()
    {
        return Transaction::where('type', 'credit')->get()->sum('amount');
    }

    public function totalDebit()
    {
        return Transaction::where('type', 'debit')->get()->sum('amount');
    }

    public function getMonthAttribute()
    {
        return $this->date->format('F');
    }

    public function getMonthNumberAttribute()
    {
        return $this->date->format('n');
    }

    public function getYearAttribute()
    {
        return $this->date->format('Y');
    }

    public function scopeTillNow($query)
    {
        return $query->where('date', '<=', Carbon::now()->endOfMonth());
    }

    public function scopeThisYear($query)
    {
        return $query->where('date', '>=', Carbon::now()->firstOfYear());
    }


    /**
     * Return Amount in Rands not Cents
     * 
     * @return float Amount
     */
    public function getAmountAttribute()
    {
        return $this->attributes['amount'] / 100;
    }

    public function setAmountAttribute($amount)
    {
        return $this->attributes['amount'] = $amount * 100;
    }

    /**
     * Get Amount as currency
     * 
     * @param  integer
     * 
     * @return string
     */
    public function amountAsCurrency()
    {        
        return number_format($this->amount, 2);
    }
}
