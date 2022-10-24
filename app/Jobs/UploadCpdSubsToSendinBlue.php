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

class UploadCpdSubsToSendinBlue extends Job implements SelfHandling, ShouldQueue
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
        $this->storeSubscriptionUserData();
    }

    private function storeSubscriptionUserData()
    {
        $data = [
            'email' => $this->subscriber->user['email'],
            'attributes' => [
                'FIRSTNAME' => $this->subscriber->user['first_name'],
                'LAST_NAME' => $this->subscriber->user['last_name'],
                'CELL' => $this->subscriber->user['cell'],
                'STATUS' => $this->subscriber->user['status'],
                'PACKAGE' => $this->subscriber->plan->name,
                'PROFESSIONAL_BODY_NAME' => ($this->subscriber->user->body ? $this->subscriber->user->body->title : "N/A"),
                'ADDRESS_CITY' => ($this->subscriber->user->addresses ? $this->subscriber->user->addresses->first()->city : "N/A"),
                'PROVINCE' => ($this->subscriber->user->addresses ? $this->subscriber->user->addresses->first()->province : "N/A"),
                'ADDITIONAL_PROFESSIONAL_BODIES' => $this->subscriber->user->extraProfessionalBodies(),
            ],
            'listIds' => [$this->plan->list_id]
        ];

        $this->sendingblueRepository->createUpdateContact($data);
        
        if($this->subscriber->name == 'cpd')
        {
            $this->subscriber->user->list_id = $this->plan->list_id;
            $this->subscriber->user->save();
        }
        
    }
}
