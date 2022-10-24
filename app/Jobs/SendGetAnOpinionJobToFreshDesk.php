<?php

namespace App\Jobs;

use App\Freshdesk;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendGetAnOpinionJobToFreshDesk extends Job implements SelfHandling, ShouldQueue
{
    public $ticket;
    public $content;

    /**
     * Create a new job instance.
     *
     * @param $ticket
     * @param $content
     */
    public function __construct($ticket, $content)
    {
        $this->ticket = $ticket;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content = $this->content;
        $ticket = $this->ticket;

        $freshticket = Freshdesk::create_ticket($content);
        $freshRespond = json_decode($freshticket->getBody()->getContents(), 2);

        $freshId = $freshRespond['id'];
        $freshReqId = $freshRespond['requester_id'];

        $user = $ticket->user->first();
        $user->freshdesk_user = $freshReqId;
        $user->save();

        $ticket->fresh_ticket_id = $freshId;
        $ticket->save();
    }
}
