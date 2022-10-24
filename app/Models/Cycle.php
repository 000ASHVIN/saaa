<?php

namespace App\Models;

use App\Body;
use App\Models\Designation;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property mixed $user
 */

class Cycle extends Model
{
    protected $table = 'cycles';
    protected $guarded = [];
    protected $appends = [
        'tax',
        'ethics',
        'auditing',
        'verifiable',
        'accounting',
        'unstructed',
        'non_verifiable',
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    /*
     * This belongs to many users
     */
    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function current()
    {
        return $this->where('current', true)->first();
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function body()
    {
        return $this->belongsTo(Body::class);
    }

    /*
     * Setting the values for the options.
     */

    public function getTaxAttribute()
    {
        $amount = $this->user[0]->cpds()->whereBetween('date', [Carbon::parse($this->start_date), Carbon::parse($this->end_date)])->where('category', 'tax')->sum('hours');
        if ($amount > $this->designation->tax){
            $amount = $this->designation->tax;
        }
        $amount = empty($amount)?0:$amount;
        return $amount;
    }

    public function getEthicsAttribute()
    {
        $amount = $this->user[0]->cpds()->whereBetween('date', [Carbon::parse($this->start_date), Carbon::parse($this->end_date)])->where('category', 'ethics')->sum('hours');
        if ($amount > $this->designation->ethics){
            $amount = $this->designation->ethics;
        }
        $amount = empty($amount)?0:$amount;
        return $amount;
    }

    public function getAccountingAttribute()
    {
        $amount = $this->user[0]->cpds()->whereBetween('date', [Carbon::parse($this->start_date), Carbon::parse($this->end_date)])->where('category', 'accounting')->sum('hours');
        if ($amount > $this->designation->accounting){
            $amount = $this->designation->accounting;
        }
        $amount = empty($amount)?0:$amount;
        return $amount;
    }

    public function getAuditingAttribute()
    {
        $amount = $this->user[0]->cpds()->whereBetween('date', [Carbon::parse($this->start_date), Carbon::parse($this->end_date)])->where('category', 'auditing')->sum('hours');
        if ($amount > $this->designation->auditing){
            $amount = $this->designation->auditing;
        }
        $amount = empty($amount)?0:$amount;
        return $amount;
    }

    public function getVerifiableAttribute()
    {
        $amount = $this->user[0]->cpds()->whereBetween('date', [Carbon::parse($this->start_date), Carbon::parse($this->end_date)]) ->where(function ($query) {
            $query->where('verifiable', true)
                ->whereNotNull('attachment')
                ->orWhere('has_certificate', true);
        })->sum('hours');
        if ($amount > $this->designation->verifiable){
            $amount = $this->designation->verifiable;
        }
        $amount = empty($amount)?0:$amount;
        return $amount;
    }

    public function getUnstructedAttribute()
    {
        $amount = $this->user[0]->cpds()->whereBetween('date', [Carbon::parse($this->start_date), Carbon::parse($this->end_date)])->where('category', 'unstructed')->sum('hours');
        if ($amount > $this->designation->unstructed){
            $amount = $this->designation->unstructed;
        }
        $amount = empty($amount)?0:$amount;
        return $amount;
    }

    public function getNonVerifiableAttribute()
    {
        $amount = $this->user[0]->cpds()->whereBetween('date', [Carbon::parse($this->start_date), Carbon::parse($this->end_date)])->where(function ($query) {
            $query->where('verifiable', false)
                ->Where('has_certificate', false);
        })->sum('hours');
        if ($amount > $this->designation->non_verifiable){
            $amount = $this->designation->non_verifiable;
        }
        $amount = empty($amount)?0:$amount;
        return $amount;
    }

    public function getTotalHoursAttribute()
    {
        return array_sum([
            ($this->designation->tax > 1) ? $this->tax : 0,
            ($this->designation->ethics > 1) ? $this->ethics : 0,
            ($this->designation->auditing > 1) ? $this->auditing : 0,
            ($this->designation->accounting > 1) ? $this->accounting : 0,
        ]);
    }
}
