<?php

namespace App\Console\Commands;

use App\Users\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class sendInvoiceToAllOpenAndUnpaidInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:all-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send an email to all unpaid invoices on the system';

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
        $users = User::whereNull('payment_method')->orWhere('payment_method', '!=', 'debit_order')->get()->filter(function($user) {
            if(count($user->overdueInvoices()))
                return $user;
        });

        $this->warn('We have ' . count($users) . ' with outstanding invoices.');

        foreach ($users as $user) {
            try {
                if(sendMailOrNot($user, 'invoices.overdue')) {
                Mail::send('emails.invoices.overdue', ['user' => $user ], function ($m) use ($user) {
                    $m->from(config('app.email'), config('app.name'));
                    if ($user->billing_email_address){
                        $m->cc(explode(",", str_replace(';', ',', $user->billing_email_address)), null);
                    }
                    $m->to($user->email, $user->first_name)->subject('Kindly settle your outstanding invoices');
                });
                $this->info("{$user->first_name} has been notified about his overdue invoices");
                }
            } catch (\Exception $e) {
                $this->error("Could not send email to {$user->id} using email {$user->email}");
            }
        }
    }
}
