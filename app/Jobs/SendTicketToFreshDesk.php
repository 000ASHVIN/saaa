<?php

namespace App\Jobs;

use App\Freshdesk;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTicketToFreshDesk extends Job implements SelfHandling, ShouldQueue
{
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        Freshdesk::create_ticket($this->data);
    }
}
