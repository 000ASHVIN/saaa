<?php

namespace App\Console\Commands;

use App\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDebtEmailToOverdueInvoices extends Command
{

    protected $signature = 'send:debt_email';

    protected $description = 'This will send a personalized email to all people owing us money.';

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

        $data = collect();

        // Without = 4714
        // with = 3947
//        $users = User::with('transactions')->where('created_at', '<', Carbon::parse('1 January 2017'))->get();

        $users = User::with('transactions')->where('email', 'tiaant@saiba.org.za')->get();
        $users->each(function($user) use($data) {
            $balance = $user->transactions()->where('type', 'debit')
                    ->sum('amount') - $user->transactions()
                    ->where('type', 'credit')
                    ->sum('amount');

            if($balance > 0) {
                $data->push([
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'balance' => $balance
                ]);
            }
        });

        foreach ($data as $user){
            $this->info("{$user['first_name']} has been notified about this email");
            if(sendMailOrNot($user, 'accounts.debtemail')) {
            Mail::send('emails.accounts.debtemail', ['user' => $user ], function ($m) use ($user) {
                $m->from(config('app.email'), config('app.name'));
                $m->to($user['email'], $user['first_name'])->subject('Your assistance please');
            });
            }
        }
    }
}
