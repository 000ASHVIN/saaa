<?php

namespace App\Http\Controllers;

use App\Users\User;
use Auth;
use Session;

class ImpersonateController extends Controller
{
    public function user_switch_start($id)
    {
        if (auth()->user() && auth()->user()->can('access-admin-section')){
            $new_user = User::find($id);

            Session::put('orig_user', auth()->user()->id );
            Session::put('impersonate_user', $new_user->id);

            if (isset($_COOKIE['upcoming_renewal_popup'])) {
                unset($_COOKIE['upcoming_renewal_popup']);
                setcookie('upcoming_renewal_popup', null, -1, '/');
            }

            Auth::login($new_user);
            alert()->success('You are impersonating '.$new_user->first_name.' '.$new_user->last_name, 'Welcome!');
        }
        return redirect()->route('dashboard');
    }

    public function user_switch_stop()
    {
        $impersonatedUserId = Session::pull('impersonate_user');
        $impersonatedUser = User::find($impersonatedUserId);

        $id = Session::pull('orig_user');
        $orig_user = User::find( $id );

        Auth::login( $orig_user );
        alert()->success('Impersonation has stopped successfully', 'Success!');
        return redirect()->route('admin.members.show', $impersonatedUser->id);
    }
}
