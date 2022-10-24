<?php

namespace App\Console\Commands;

use App\Newsletters\Subscriber;
use App\Repositories\Sendinblue\SendingblueRepository;
use Illuminate\Console\Command;

class MigrateAllLocalSubscribersToSendinBlue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:sendinblue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will migrate all local website subscribers to SendinBlue';
    /**
     * @var SendingblueRepository
     */
    private $sendingblueRepository;

    /**
     * Create a new command instance.
     * @param SendingblueRepository $sendingblueRepository
     */
    public function __construct(SendingblueRepository $sendingblueRepository)
    {
        parent::__construct();
        $this->sendingblueRepository = $sendingblueRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscribers = Subscriber::all()->unique('email');
        $this->info('We have '.count($subscribers));
        foreach ($subscribers as $subscriber){
            $this->info('We are migrating '.$subscriber->email);
            $this->sendingblueRepository->createSubscriber($subscriber, $listId = [9]);
        }
    }
}
