<?php

namespace App\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Sendinblue\Mailin;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\ContactsApi;
use GuzzleHttp\Client;
use Exception;
use App\Repositories\Sendinblue\SendingblueRepository;

class AssignWebinarAttendeesToSendinBlue extends Job implements SelfHandling, ShouldQueue
{
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 1200;
    private $tickets;
    private $folder;
    private $sendingblueRepository;

    public function __construct($tickets, $folder)
    {
        $this->tickets = $tickets;
        $this->folder = $folder;
        $this->sendingblueRepository = new SendingblueRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tickets = $this->tickets;
        $folderId = $this->folder['id'];

        $filteredTickets = $tickets->filter(function ($ticket) {
            if (!$ticket->invoice && $ticket->venue->type == 'online') {
                return $ticket;
            } elseif ($ticket->invoice && $ticket->invoice->status == 'paid' && $ticket->venue->type == 'online') {
                return $ticket;
            }
        });

        foreach ($filteredTickets as $ticket) {
            $data = [
                'email' => $ticket->user['email'],
                'attributes' => [
                    'FIRSTNAME' => $ticket->user['first_name'],
                    'LAST_NAME' => $ticket->user['last_name'],
                ],
                'listIds' => [$folderId]
            ];
            $this->sendingblueRepository->createUpdateContact($data);

        }

        // Send Notification to inform me that the contacts has finished importing.
        $this->sendNotification($filteredTickets->first()->event, $folderId);
    }

    public function sendNotification($event, $folderId)
    {
        Mail::send('contacts.uploaded', ['event' => $event, 'folderId' => $folderId], function ($m) use ($event, $folderId) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.to_email'))->subject('SendinBlue Import Completed');
        });
    }
}
