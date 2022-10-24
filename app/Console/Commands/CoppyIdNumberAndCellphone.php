<?php

namespace App\Console\Commands;

use App\Users\User;
use Illuminate\Console\Command;

class CoppyIdNumberAndCellphone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coppy:id_numbers';
    protected $description = 'This will coppy existing ID numbers and phone numbers to the users table.';

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
        $users = User::with('profile')->get();
        foreach ($users as $user){
            if ($user->profile){
                if ($user->profile->id_number){
                    $this->info('updating'.' '.$user->first_name.' '.'ID Number');
                        $profile_id = $user->profile->id_number;
                        $user->update(['id_number' => $profile_id]);
                        $user->save();
                }
                if ($user->profile->cell){
                    $this->info('updating'.' '.$user->first_name.' '.'Cellphone Number');
                        $profile_cell = $user->profile->cell;
                        $user->update(['cell' => $profile_cell]);
                        $user->save();
                }
            }
        }
    }
}
