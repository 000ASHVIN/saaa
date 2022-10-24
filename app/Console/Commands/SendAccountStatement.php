<?php

namespace App\Console\Commands;
error_reporting(0);

use App\Jobs\SendAccountStatementJOb;
use App\Services\Invoicing\SendPdfInvoice;
use App\Users\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SendAccountStatement extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statement:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send all account statements to users on our system';
    /**
     * @var SendPdfInvoice
     */

    /**
     * Create a new command instance.
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
        $emailMessage =
            'Please note that there is an amount outstanding on your account and is immediately payable in order to avoid suspension of your account.
            
            We have attached your account statement to this email for your convenience. 
            
            Should you wish to settle your outstanding Invoices via EFT ("Electronic Funds Transfer"), please use our banking details below, alternatively please login to your profile and settle your outstanding invoices with your credit card.
            
            BANKING DETAILS
            Bank: ABSA Bank
            Account Holder: SA Accounting Academy (Pty) Ltd
            Account Number: 4077695135
            Branch Code: 632005
            Reference: Outstanding Invoice refference';


        $overdue = User::whereNull('payment_method')->orWhere('payment_method', '!=', 'debit_order')->get()->filter(function($user) {
            if(count($user->overdueInvoices()))
                return $user;
        });

        foreach ($overdue as $user){
            $job = (new SendAccountStatementJOb($user, $emailMessage));
            $this->info('Sending Statement to '.$user->email);
            $this->dispatch($job);
            try {

            }catch (\Exception $e) {
                $this->error("Could not send email to {$user->id} using email {$user->email}.");
            }
        }
    }
}
