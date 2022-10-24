<?php

namespace App\Console\Commands;

use App\Users\User;
use Illuminate\Console\Command;
use Mail;

class SendOverdueInvoiceEmail extends Command
{
    protected $signature = 'overdue:invoices';
    protected $description = 'Let\'s send an email to all users who did not pay us yet!';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the command.
     *
     */
    public function handle()
    {
        $users = User::whereNull('payment_method')
            ->orWhere('payment_method', '!=', 'debit_order')
            ->where('handed_over', '!=', true)
            ->get()
            ->filter(function ($user) {
                if (count($user->overdueInvoices())) {
                    return $user;
                }
            });

        $this->warn('We have ' . count($users) . ' with outstanding invoices.');

        foreach ($users as $user) {
            try {
                if(sendMailOrNot($user, 'invoices.overdue')) {
                Mail::send('emails.invoices.overdue', ['user' => $user], function ($m) use ($user) {
                    $m->from(config('app.email'), config('app.name'));
                    if ($user->billing_email_address) {
                        $m->cc(explode(',', str_replace(';', ',', $user->billing_email_address)), null);
                    }
                    $m->to($user->email, $user->first_name)->subject('Kindly settle your outstanding invoices!');
                });
                $this->info("{$user->first_name} has been notified about his overdue invoices");
                }
            } catch (\Exception $e) {
                $this->error("Could not send email to {$user->id} using email {$user->email}");
            }
        }

        try {
            Mail::send('emails.invoices.report', ['users' => $users], function ($m) use ($users) {
                $m->from(config('app.email'), config('app.name'));
                $m->to(config('app.email'), $users)->subject('Overdue Invoices Report');
            });
        } catch (\Exception $e) {
            $this->error('Could not send report...');
        }
    }
}
