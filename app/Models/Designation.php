<?php

namespace App\Models;
use App\Body;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float|int $total_hours
 * @property mixed $body
 */

class Designation extends Model
{
    protected $table = 'designations';
    protected $guarded = [];

    public function body()
    {
        return $this->belongsTo(Body::class);
    }

    public function getTotalHoursAttribute()
    {
        return array_sum([
            $this['tax'],
            $this['ethics'],
            $this['auditing'],
            $this['accounting'],
        ]);
    }

    public function cycles()
    {
        return $this->belongsToMany(Cycle::class);
    }
    /*
     * Planning
     * $designation->categories
     * -----------------------------
     *  Accounting : 20 hours
     *      -> Filter User CPD records by category and count the amount of hours for specified categories.
     *  Tax: 15 Hours
     * -------------------------------
     *
     */
}
