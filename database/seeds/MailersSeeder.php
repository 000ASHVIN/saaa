<?php

use App\Mailer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class MailersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Mailer::create([
            'title' => 'New membership and one time password',
            'slug' => 'new-membership-and-one-time-password',
            'view' => 'mailers.member-imported-with-one-time-password'
        ]);
        Model::reguard();
    }
}
