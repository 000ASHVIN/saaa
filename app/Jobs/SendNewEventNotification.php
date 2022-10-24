<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Mail\Mailer;

class SendNewEventNotification extends Job implements SelfHandling
{

    private $user;
    private $event;
    private $currentPlan;
    private $currentPlanEvents;
    private $purchasedEventIds;
    private $userType;
    private $userCompany;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $event, $currentPlan, $currentPlanEvents, $purchasedEventIds, $userType, $userCompany)
    {
        $this->user = $user;
        $this->event = $event;
        $this->currentPlan = $currentPlan;
        $this->currentPlanEvents = $currentPlanEvents;
        $this->purchasedEventIds = $purchasedEventIds;
        $this->userType = $userType;
        $this->userCompany = $userCompany;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $user = $this->user;
        if(sendMailOrNot($user, 'events.new_event_notification')) {
        $mailer->send('emails.events.new_event_notification', [ 
            'user' => $user, 
            'event' => $this->event, 
            'allevent' => $this->currentPlanEvents, 
            'plan' => $this->currentPlan,
            'currentPlanEvents'=>$this->currentPlanEvents,
            'purchased_event_ids'=>$this->purchasedEventIds,
            'userType' => $this->userType,
            'userCompany' => $this->userCompany
        ], function ($m) use($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($user->email)->subject('New Event loaded to your subscription Plan');
        });
        }
    }
}
