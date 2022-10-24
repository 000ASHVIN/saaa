<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Mail\Mailer;
use Carbon\Carbon;
class SendEventBookingReminder extends Job implements SelfHandling
{

    protected $user;
    protected $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $event)
    {
        $this->user = $user;
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $user = $this->user;
        $event = $this->event;
        $eventDate = date_format($event->upcomingVenue()->pricings->first()->actualDate(), 'd F Y') . ' ' . Carbon::parse($event->start_time)->format('H:i') . ' - ' . Carbon::parse($event->end_time)->format('H:i');
        $subject = 'Reminder:' . ' ' . $event->name . ' ,' . $eventDate;
        $eventName = $event->name;

        // Send reminder email
        if(sendMailOrNot($user, 'events.event_booking_reminder')) {
        $mailer->send('emails.events.event_booking_reminder', [
                'user' => $user,
                'event' => $event,
                'EventName' => $eventName, 
                'EventDate' => $eventDate, 
                'subject' => $subject
            ], 
            function ($m) use ($user, $eventName, $subject) {
                $m->from(env('APP_EMAIL'), config('app.name'));
                $m->to($user->email)->subject($subject);
            }
        );
        }
    }
}
