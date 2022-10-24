<?php

namespace App\AppEvents;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use MaddHatter\LaravelFullcalendar\IdentifiableEvent;

/**
 * Class Venue
 * @package App\AppEvents
 */
class Venue extends Model implements IdentifiableEvent
{

    /**
     * @var string
     */
    protected $table = 'venues';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'address_line_one',
        'address_line_two',
        'city',
        'province',
        'country',
        'area_code',
        'type',
        'is_active',
        'max_attendees',
        'min_attendees',
        'start_time',
        'end_time'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pricings()
    {
        return $this->HasMany(Pricing::class);
    }

    public function hasPriceRange()
    {
        $pricings = $this->removeBodyPricings();
        return count($pricings) > 1;
    }

    public function getMinPrice()
    {
        $pricings = $this->removeBodyPricings();
        $minPrice = $pricings[0]->price;

        foreach ($pricings as $pricing) {
            if ($pricing->price < $minPrice)
                $minPrice = $pricing->price;
        }
        return $minPrice;
    }

    public function getMaxPrice()
    {
        $pricings = $this->removeBodyPricings();
        $maxPrice = 0;
        foreach ($pricings as $pricing) {
            if ($pricing->price > $maxPrice)
                $maxPrice = $pricing->price;
        }
        return $maxPrice;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dates()
    {
        return $this->hasMany(Date::class);
    }
    public function activeDates()
    {
        return $this->dates()->where('is_active',true);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function removeBodyPricings()
    {
        $pricings = collect();
        $this->pricings()->get()->each(function ($pricing) use ($pricings) {
            if (!$pricing->bodies->count()) {
                $pricings->push($pricing);
            }
        });
        return $pricings;
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        // TODO: Implement isAllDay() method.
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        // TODO: Implement getStart() method.
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        // TODO: Implement getEnd() method.
    }

    /**
     * Get the event's ID
     *
     * @return int|string|null
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }
}