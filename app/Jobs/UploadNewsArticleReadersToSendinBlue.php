<?php

namespace App\Jobs;

use App\AppEvents\Event;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Sendinblue\Mailin;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\ContactsApi;
use GuzzleHttp\Client;
use Exception;
use App\Repositories\Sendinblue\SendingblueRepository;

class UploadNewsArticleReadersToSendinBlue extends Job implements SelfHandling, ShouldQueue
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 1200;
    private $users;
    private $folder;
    private $sendingblueRepository;

    public function __construct($users, $folder)
    {
        $this->users = $users;
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
        $users = $this->users;
        $folderId = $this->folder['id'];

        foreach ($users as $user){
            $data = [
                'email' => $user['email'],
                'attributes' => [
                    'FIRSTNAME' => $user['first_name'],
                    'LAST_NAME' => $user['last_name'],
                ],
                'listIds' => [$folderId]
            ];
            // $this->call()->create_update_user($data);
            $this->sendingblueRepository->createUpdateContact($data);
        }
        
    }
}
