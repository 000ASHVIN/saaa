<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sendinblue\Mailin;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\ContactsApi;
use GuzzleHttp\Client;
use Exception;
use App\Repositories\Sendinblue\SendingblueRepository;

class UnlinkSubscriberFromList extends Job implements SelfHandling, ShouldQueue
{
    public $plan;
    public $subscriber;
    private $sendingblueRepository;
    /**
     * Create a new job instance.
     *
     * @param $plan
     * @param $subscriber
     */
    public function __construct($plan, $subscriber)
    {
        $this->plan = $plan;
        $this->subscriber = $subscriber;
        $this->sendingblueRepository = new SendingblueRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->unlinkSubscriberFromList($this->subscriber->user->list_id, $this->subscriber);
    }

    private function unlinkSubscriberFromList($id, $subscriber)
    {
        try {
            $data = [
                'email' => $subscriber->user['email'],
                'listid_unlink' => [$id]
            ];
    
            $identifier = $subscriber->user['email'];
            $newData['unlinkListIds'] = [$id];
            
            $this->sendingblueRepository->updateContact($identifier, $newData);
            return $data;
        } catch (Exception $e) {}
    }
}
