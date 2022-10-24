<?php
namespace App\Repositories\InviteRepository;

use App\Invite;
use Illuminate\Support\Facades\Mail;

class InviteRepository
{
    public function invite($request, $token)
    {

        if(auth()->user()->company->staff->contains('email', str_replace(' ','',$request['email'])) != true){

            // To check pending invites
            $invite = New  Invite();
            if($request['id_number'] != ""){
                $invite = $invite->where('id_number', $request['id_number'])->orWhere('email', str_replace(' ','',$request['email']))->first();
            }else{
                $invite = $invite->Where('email', str_replace(' ','',$request['email']))->first();
            }
            
            if (is_null($invite)){
                $invite = Invite::create([
                    'cell' => $request['cell'],
                    'email' => $request['email'],
                    'id_number' => $request['id_number'],
                    'last_name' => $request['last_name'],
                    'first_name' => $request['first_name'],
                    'company_id' => auth()->user()->company->id,
                    'alternative_cell' => $request['alternative_cell'],
                    'token' => $token
                ]);
                return $invite;
            }
            return null;
        }else{
            return 'exists';
        }
    }

    public function sendInvite($email, Invite $invite)
    {
        if(sendMailOrNot($email, 'invites.invite')) {
        Mail::send('emails.invites.invite', ['invite' => $invite ], function ($m) use ($email) {
            $m->from(config('app.email'), config('app.name'));
            $m->to([$email])->subject('Your Invitation to join my company');
        });
        }
    }

    public function sendNewProfileEmail($email, $user)
    {
        if(sendMailOrNot($email, 'invites.new_profile')) {
        Mail::send('emails.invites.new_profile', ['user' => $user ], function ($m) use ($user, $email) {
            $m->from(config('app.email'), config('app.name'));
            $m->to([$email])->subject('Your New Account');
        });
        }
    }
}