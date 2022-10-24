<?php

namespace App\Console\Commands;

use App\Users\User;
use Illuminate\Console\Command;

class CheckForDuplicateVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will check for duplicate videos on the users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::has('webinars')->where('deleted_at', null)->get();
        dd(count($users));
    }
}
