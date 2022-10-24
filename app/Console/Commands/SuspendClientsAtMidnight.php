<?php

namespace App\Console\Commands;

use App\Users\User;
use Illuminate\Console\Command;
use App\Moodle;

class SuspendClientsAtMidnight extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suspend:midnight';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will suspend all accounts at midnight that owes us money';

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
        $users = User::with('invoices','invoices.transactions')->chunk(500, function($users) {
            foreach ($users as $user) {
                if ($user->overdueInvoices()->where('type', 'subscription')->count() > 0){
                    if ($user->status == 'active'){
                        $this->info('Suspending '.$user->email);
                        $user->update(['status' => 'suspended']);
                        if($user->moodle_user_id > 0) {
                            $moodleUser = new \stdClass();
                            $moodleUser->id = (int)$user->moodle_user_id;
                            $moodleUser->suspended = 1;
            
                            $moodle = new Moodle();
                            $response = $moodle->userUpdate($moodleUser);
                        }
                        return $user;
                    }
                }
                else{
                    if ($user->overdueInvoices()->where('type', 'subscription')->count() == 0){
                        if ($user->status == 'suspended'){
                            $user->update( ['status' => 'active', 'suspended_at' => null]);
                            if($user->moodle_user_id > 0) {
                                $moodleUser = new \stdClass();
                                $moodleUser->id = (int)$user->moodle_user_id;
                                $moodleUser->suspended = 0;
                
                                $moodle = new Moodle();
                                $response = $moodle->userUpdate($moodleUser);
                            }
                            return $user;                   
                        }
                    }
                }

                if ($user->overdueInvoices()->where('type', 'course')->count() > 0){
                    if($user->moodle_user_id > 0) {
                        $moodleUser = new \stdClass();
                        $moodleUser->id = (int)$user->moodle_user_id;
                        $moodleUser->suspended = 1;
        
                        $moodle = new Moodle();
                        $response = $moodle->userUpdate($moodleUser);
                    }
                }
                else{
                    if ($user->overdueInvoices()->where('type', 'course')->count() == 0){
                        if($user->moodle_user_id > 0) {
                            $moodleUser = new \stdClass();
                            $moodleUser->id = (int)$user->moodle_user_id;
                            $moodleUser->suspended = 0;
            
                            $moodle = new Moodle();
                            $response = $moodle->userUpdate($moodleUser);
                        }
                    }
                }
            }
        });
        $this->info('We have suspended '.count($users));
    }
}
