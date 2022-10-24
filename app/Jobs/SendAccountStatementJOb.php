<?php

namespace App\Jobs;

use App\Users\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
class SendAccountStatementJOb extends Job implements SelfHandling, ShouldQueue
{
    private $message;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $emailMessage = $this->message;
        $user = $this->user;

        if (! $emailMessage){
            if(sendMailOrNot($user, 'accounts.statement')) {
            $mailer->send('emails.accounts.statement', ['user' => $this->user ], function ($m) use ($user) {
                $m->from(config('app.email'), config('app.name'));
                if ($this->user->billing_email_address){
                    if(strpos($this->user->billing_email_address,"@") == true && strpos($this->user->billing_email_address," ") != true){
                        $m->cc(explode(",", str_replace(';', ',', $this->user->billing_email_address)), null);
                    }
                }
                $m->to($user->email, $user->first_name.' '.$user->last_name)->subject('Outstanding balance for settlement');
            });
            }
        }else{
            $pdf = App::make('snappy.pdf.wrapper');
            $pdf->loadView('emails.accounts.no_top_statement', ['user' => $user]);
            $pdf->save(public_path('assets/frontend/statements/statement-' . $user->id . '.pdf'));
            $location = public_path('assets/frontend/statements/statement-' . $user->id . '.pdf');

            if(sendMailOrNot($user, 'accounts.blank_statement_email')) {
            $mailer->send('emails.accounts.blank_statement_email', ['user' => $this->user, 'emailMessage' => $emailMessage], function ($m) use ($user, $location) {
                $m->from(config('app.email'), config('app.name'));
                if ($this->user->billing_email_address){
                    if(strpos($this->user->billing_email_address,"@") == true && strpos($this->user->billing_email_address," ") != true){
                        $m->cc(explode(",", str_replace(';', ',', $this->user->billing_email_address)), null);
                    }
                }
                $m->to($user->email, $user->first_name.' '.$user->last_name)->subject('Your Statement');
                $m->attach($location);
            });
            }
            File::delete($location);
        }
    }
}
