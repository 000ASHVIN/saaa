<?php

namespace App\AppEvents;

use Illuminate\Database\Eloquent\Model;
use App\AppEvents\Event;

class EventNotification extends Model
{
    /**
     * @var string
     */
    protected $table = 'event_notification_schedule';

    protected $fillable = ['event_id', 'schedule_date', 'status', 'emails_sent'];

    protected $appends = ['status_text'];

    public function event()
    {
    	return $this->belongsTo(Event::class);
    }

    public function getStatusTextAttribute() {

        $statuses = [
            'not_scheduled' => 'Not Scheduled',
            'in_progress' => 'In Progress',
            'scheduled' => 'Scheduled',
            'completed' => 'Completed'
        ];

        $status = isset($statuses[$this->status])?$statuses[$this->status]:'-';
        return $status;
        
    }

}
