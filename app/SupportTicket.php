<?php

namespace App;

use App\Users\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Traits\ActivityTrait;

class SupportTicket extends Model implements SluggableInterface
{
    use SoftDeletes, SluggableTrait, SearchableTrait, ActivityTrait;

    protected $table = 'support_tickets';

    protected $guarded = [];
    protected $searchable = [
        'columns' => [
            'subject' => 10,
            'description' => 8,
        ]
    ];

    protected $sluggable = [
        'build_from' => 'subject',
        'save_to' => 'slug',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ticket) {
            // Set Priority
            $ticket->priority = 'low';

            // Set Expired Date
            $expireDate = Carbon::now()->addDays(30)->endOfDay();
            $ticket->expired_at = $expireDate;

            // Set refference
            $reference = $ticket->getNextReference();
            $ticket->reference = $reference;
        });
    }

    public function getNextReference()
    {
        $latest = static::latest('id')->first();
        if ($latest) {
            $ref = (int)$latest->reference += 1;
            return str_pad($ref, 6, '0', STR_PAD_LEFT);
        } else {
            return str_pad(1, 6, '0', STR_PAD_LEFT);
        }
    }

    /*
     * This belongs to a thread.
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /*
     * This belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setAgentId($id)
    {
        $this->update(['agent_id' => $id]);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }
}
