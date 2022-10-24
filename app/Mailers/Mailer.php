<?php 

namespace App\Mailers;

use App\Users\User;
use Illuminate\Mail\Mailer as Mail;

/**
 * Class Mailer
 * @package App\Mailers
 */
abstract class Mailer
{
    /**
     * @var Mail
     */
    public $mail;

    /**
     * Mailer constructor.
     * @param Mail $mail
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Send Email to User
     * @param  User $user User to send mailer to
     * @param  String $subject Subject Line
     * @param  String $view View name
     * @param  array $data Data associated with view
     * @param null $attachment
     */
    public function sendTo($user, $subject, $view, $data = [], $attachment = null)
    {
        if(sendMailOrNot($user, $view)) {
        $this->mail->send($view, $data, function($message) use ($user, $subject, $attachment)
        {
            if ($user->billing_email_address){
                $message->to($user->email);
                if(strpos($user->billing_email_address,"@") == true && strpos($user->billing_email_address," ") != true){
                    $message->cc(explode(",", str_replace(';', ',', $user->billing_email_address)), null)->subject($subject);
                }
            }else{
                $message->to($user->email)->subject($subject);
            }
            if($attachment)
                $message->attach($attachment);
        });
        }
    }
}