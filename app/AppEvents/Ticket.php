<?php

namespace App\AppEvents;

use App\Billing\Invoice;
use App\InvoiceOrder;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Users\Cpd;

/**
 * Class Ticket
 * @package App\AppEvents
 */

class Ticket extends Model
{
    use SoftDeletes, SearchableTrait;

    /**
     * @var string
     */
    protected $table = 'tickets';

    protected $searchable = [
        'columns' => [
            'events.name' => 10,
            'events.description' => 8,
            'events.short_description' => 5,
        ],
        'joins' => [
            'events' => ['tickets.event_id', 'events.id']
        ]
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['package', 'start_date', 'event_year'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed(); 
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dates()
    {
        return $this->belongsToMany(Date::class);
    }

    public function extras()
    {
        return $this->belongsToMany(Extra::class);
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }

    public function dietaryRequirement()
    {
        return $this->belongsTo(DietaryRequirement::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getPackageAttribute()
    {
        return ($this->user->subscription('cpd'))? $this->user->subscription('cpd')->plan->name.' '.ucfirst($this->user->subscription('cpd')->plan->interval).'ly' : "No Plan";
    }

    public function getDietaryRequirementNameAttribute()
    {
        if ($this->dietaryRequirement){
            return $this->dietaryRequirement->name;
        }else{
            return '-';
        }
    }

    public function getStartDateAttribute()
    {
        if(! $this->dates()->first()) {
            return $this->created_at->format('Y-m-d');
        }
        return Carbon::parse($this->dates()->first()->date);
    }

    public function getEventYearAttribute()
    {
        if(! $this->event) {
            return $this->created_at->format('Y');
        }
        if(! $this->dates()->first()) {
            return $this->created_at->format('Y');
        }
        return Carbon::parse($this->dates()->first()->date)->format('Y');
    }

    public function invoice_order()
    {
        return $this->belongsTo(InvoiceOrder::class);
    }

    public function cpd() {
        return $this->hasOne(Cpd::class, 'ticket_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($ticket) { 
            $ticket->calculateEventComplete();
        });
    }

    public function calculateEventComplete() {

        $ticket = $this;
        $pricing = $ticket->pricing;
        $event = $ticket->event;
        $user = $ticket->user;
        $event_complete = 1;
        if($pricing && $event) {

            // Check cpd claimed or not
            if($pricing->can_manually_claim_cpd && $pricing->cpd_hours>0) {

                if(!$ticket->cpd) {
                    $event_complete = 0;
                }

            }

            // Check assessments completed or not
            $assessments = $event->assessments;
            if(count($assessments)) {
                foreach($assessments as $assessment) {
                    if(!$assessment->hasBeenPassedByUser($user)){
                        $event_complete = 0;
                    }
                }
            }
            $ticket->event_complete = $event_complete;
            $ticket->save();
        }

    }

}