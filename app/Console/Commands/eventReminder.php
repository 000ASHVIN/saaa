<?php

namespace App\Console\Commands;

use DateTime;
use Carbon\Carbon;
use App\Users\User;
use App\eventNotified;
use App\Jobs\WebinarInviteJob;
use Spatie\CalendarLinks\Link;
use Illuminate\Console\Command;
use App\AppEvents\EventRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Lists\ListRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;

class eventReminder extends Command
{
    use DispatchesJobs;

    protected $signature = 'event:reminders';
    protected $description = 'Let\'s send an email to all attendees that registered for upcoming events today or tomorrow';
    private $listRepository;
    private $eventRepository;

    public function __construct(ListRepository $listRepository, EventRepository $eventRepository)
    {
        parent::__construct();
        $this->eventRepository = $eventRepository;
        $this->listRepository = $listRepository;
    }

    public function handle()
    {
        // Get the upcoming Webinars
        $events = $this->eventRepository->upcomingWebinar();

        if (count($events) <= 0) {
            $this->info('There is no event today or tomorrow');
        } else {
            $this->info('We have ' . count($events));
        }

        // Loop through the relevant event and push the ticket user to our new user collection
        foreach ($events as $event) {
            if ($event->webinarAvailable() == true) {
                // Collect all the users that we are sending to
                $users = collect();
                $webinars = collect();
                $event->venues->where('type', 'online')->first()->pricings->take(1)->filter(function ($pricing) use ($webinars) {
                    if (count($pricing->webinars)) {
                        $webinars->push($pricing->webinars);
                    }
                });

                $filename = "invite.ics";
                $content = getIcsFileContent($event,$webinars->first());
                Storage::disk('local')->put($filename, $content);
                
                $this->loopAndPushUser($event, $users);

                // Find the staff and also send the webinar invite to them...
                foreach (User::has('roles')->get() as $staffMember){
                    $users->push($staffMember);
                }

                /** Send Email to our new users collection that we have created */
                $it = 1;
                foreach ($users->unique() as $user) {
                    $this->sendInviteTo($user, $event);
                    $this->info("{$it}. {$user->first_name} has been notified about {$event->name}");
                    $it++;
                }

                $this->info("{$users->unique()->count()} users has been notified about {$event->name} webinar, I have sent an email to Admin to confirm this");

                try {
                    Mail::send('emails.events.webinar_report', ['users' => $users, 'event' => $event], function ($m) use ($event) {
                        $m->from(env('APP_EMAIL'), config('app.name'));
                        $m->to(env('APP_TO_EMAIL'), 'System Administrator')->subject('Webinar Report: ' . date_format($event->upcomingVenue()->pricings->first()->actualDate(), 'd F Y') . ' ' . Carbon::parse($event->start_time)->format('H:i') . ' - ' . Carbon::parse($event->end_time)->format('H:i'));
                    });
                } catch (\Exception $exception) {
                    $this->error('Error, Could not send report to admin!');
                    $this->logEntry($user, $subject = '(Failed!) Unable to send email..', $event);
                }
            } else {
                $this->info("Unable to notfify users about {$event->name} due to no webinar links");
                Mail::send('emails.events.no_webinar', ['event' => $event], function ($m) use ($event) {
                    $m->from(env('APP_EMAIL'), config('app.name'));
                    $m->to(env('APP_TO_EMAIL'), 'System Administrator')->subject('No webinar Links available');
                });
            }
        }
    }

    // This will loop through the event tickets and push the ticket user to new users collection
    public function loopAndPushUser($event, $users)
    {
        foreach ($event->tickets as $ticket) {
            if ($ticket->user) {
                if ($ticket->user->settings && key_exists('event_notifications', $ticket->user->settings)) {
                    if ($ticket->invoice_order && $ticket->invoice_order->paid) {
                        if($ticket->user->status != 'cancelled'){
                            $users->push($ticket->user);
                        }
                    } elseif ($ticket->invoice && $ticket->invoice->paid) {
                        if($ticket->user->status != 'cancelled'){
                            $users->push($ticket->user);
                        }
                    } elseif (is_null($ticket->invoice) && is_null($ticket->invoice_order)) {
                        if($ticket->user->status != 'cancelled'){
                            $users->push($ticket->user);
                        }
                    }
                }
            }
        }
    }

    // Send email to users
    public function sendInviteTo($user, $event)
    {
        $date = date_format($event->upcomingVenue()->pricings->first()->actualDate(), 'd F Y') . ' ' . Carbon::parse($event->start_time)->format('H:i') . ' - ' . Carbon::parse($event->end_time)->format('H:i');
        $subject = 'Reminder:' . ' ' . $event->name . ' ,' . $date;

        try {
            $EventName = $event->name;
            $EventDate = $date;
            $webinars = collect();
            $event->venues->where('type', 'online')->first()->pricings->take(1)->filter(function ($pricing) use ($webinars) {
                if (count($pricing->webinars)) {
                    $webinars->push($pricing->webinars);
                }
            });
            $from = DateTime::createFromFormat('Y-m-d H:i', date_format($event->upcomingVenue()->pricings->first()->actualDate(), 'Y-m-d') . $event->Start_time);
            $to = DateTime::createFromFormat('Y-m-d H:i', date_format($event->upcomingVenue()->pricings->first()->actualDate(), 'Y-m-d') . $event->end_time);
            $link = Link::create(str_replace('\'/', '', preg_replace('/[^A-Za-z0-9\-]/', ' ', $event->name)), $from, $to)
                    ->address(config('app.name'))
                    ->description(htmlentities($event->short_description));
            $link->outlook_live = getCalenderLink($event,$webinars->first(), 'outlook_live');
            $eventSlug = $event->slug; 
            $Outlook = $link->ics();
            
            $job = (new WebinarInviteJob($user, $subject, $EventName, $EventDate, $webinars->first(),$link, $eventSlug,$Outlook));
            $this->dispatch($job);
        } catch (\Exception $exception) {
            $this->error('Could not send invite to ' . $user->first_name . ' ' . $user->last_name . ' ' . $user->email);
            $this->logEntry($user, $subject = '(Failed!) Unable to send email..', $event);
        }
    }

    public function logEntry($user, $subject, $event)
    {
        eventNotified::create([
            'full_name' => $user->first_name . ' ' . $user->last_name,
            'email_address' => $user->email,
            'subscription' => ($user->subscription('cpd')->plan->name) ?: 'Free Plan',
            'mobile' => ($user->profile->cell) ?: 'None',
            'subject_line' => $subject,
            'event_name' => $event->name
        ]);
    }
}
