<?php

namespace App\AppEvents;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Date
 * @package App\AppEvents
 */
class Date extends Model
{
    protected $guarded = [];
    /**
     * @var array
     */
    protected $dates = [
		'date'
	];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue()
    {
    	return $this->belongsTo(Venue::cass);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tickets()
    {
    	return $this->belongsToMany(Ticket::class);
    }

    /**
     * @param $date
     * @return string
     */
    public function getDateAttribute($date)
    {
    	return Carbon::parse($this->attributes['date'])->toFormattedDateString();
    }
}
