<?php

namespace App\Jobs;

use App\AppEvents\Event;
use App\eventNotified;
use App\Jobs\Job;
use App\Users\User;
use Illuminate\Mail\Mailer;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class WebinarInviteJob extends Job implements SelfHandling, ShouldQueue
{

    private $user;
    private $subject;
    private $EventName;
    private $EventDate;
    private $webinars;
    private $link;
    private $event;
    private $Outlook;

    public function __construct($user, $subject, $EventName, $EventDate, $webinars,$link, $event = null,$Outlook=null)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->EventName = $EventName;
        $this->EventDate = $EventDate;
        $this->webinars = $webinars;
        $this->link = $link;
        $this->event = $event;
        $this->Outlook = $Outlook;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $user = $this->user;
        $eventDate = $this->EventDate;
        $eventName = $this->EventName;
        $webinars = $this->webinars;
        $subject = $this->subject;
        $link = $this->link;
        $event = $this->event;
        $Outlook= $this->Outlook;

        $filename = "invite.ics";

        if(sendMailOrNot($user, 'events.upcoming_webinar')) {
        $mailer->send('emails.events.upcoming_webinar', ['user' => $user, 'EventName' => $eventName, 'EventDate' => $eventDate, 'webinars' => $webinars, 'subject' => $subject, 'link'=>$link, 'event'=>$event], function ($m) use ($user, $eventName, $subject, $filename,$Outlook) {
            $m->from(env('APP_EMAIL'), config('app.name'));
            $m->to($user->email, $user->first_name.' '.$user->last_name)->subject($subject);
            $m->attach(storage_path('app/invite.ics'), array('mime' => "text/calendar"));
            $m->attachData($Outlook, 'invite.ics', [
                'Content-Type' => 'text/calendar; charset="utf-8"; method=REQUEST',
                //                'mime' => 'text/calendar; charset="utf-8"; method=REQUEST',
                                'Content-Disposition' => 'attachment; filename="invite.ics"',
            ]);
            $this->logEntry($user, $subject, $eventName);
        });
        }
    }

    public function logEntry($user, $subject, $eventName)
    {
        eventNotified::create([
            'full_name' => $user->first_name.' '.$user->last_name,
            'email_address' => $user->email,
            'subscription' => ($user->subscription('cpd')->plan->name)? : "Free Plan",
            'mobile' => ($user->profile->cell)? : "None",
            'subject_line' => $subject,
            'event_name' => $eventName
        ]);
    }
}
