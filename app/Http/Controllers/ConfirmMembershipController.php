<?php

namespace App\Http\Controllers;

use App\Jobs\sendDeclinedEmailToUser;
use App\Jobs\SendMembershipConfirmation;
use App\Users\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mockery\Exception;

class ConfirmMembershipController extends Controller
{
    /*
     * This is used to verify the membership with the profesional body.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user){
            $user->update([
                'membership_verified' => true,
                'body_responded_approved' => true
            ]);
            alert()->success('Thank you for verifying this member', 'Success!');
        }else{
            alert()->error('We could not find this member, Please contact us', 'Error!');
        }
        return redirect()->route('home');
    }

    /*
     * This is used to resend verification emails to the professional body.
     */
    public function resend($id)
    {
        $user = User::find($id);
        $job = (new SendMembershipConfirmation($user))->delay(300);
        $this->dispatch($job);

        alert()->success('Your verification email has been sent to your professional body', 'Success!');
        return back();
    }

    /*
     * We will fire an email when the membership verification has been declined.
     */
    public function decline($id)
    {
        $user = User::find($id);

        if ($user->body_responded_declined == false){
            $this->sendDeclinedEmailToMember($user);
            $user->update(['body_responded_declined' => true]);
            alert()->success('The Member has been notified', 'Success!');
        }else{
            alert()->warning('The Member was already been notified', 'Warning!');
        }
        return redirect()->route('home');
    }

    /*
     * Process the declined mailer job.
     */
    public function sendDeclinedEmailToMember($user)
    {
        $job = (new sendDeclinedEmailToUser($user));
        $this->dispatch($job);
    }
}
