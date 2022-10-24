<?php

namespace App\Http\Controllers\Admin;

use App\Note;
use App\Users\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UpdateSubscriptionPaymentMethod extends Controller
{
    /**
     * @param Request $request
     * @param $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $member)
    {
        $user = User::find($member);
        if ($user->subscription('cpd')){
            $user->update(['payment_method' => $request->payment_method]);
            alert()->success('The payment method was changed', 'success!');

            $this->createUpdateDebitOrderNote($request, $user);
            return back();
        }else{
            alert()->error('No Subscription Found', 'Error');
            return back();
        }
    }

    /**
     * @param $request
     * @param $user
     */
    public function createUpdateDebitOrderNote($request, $user)
    {
        $note = New Note([
            'type' => 'general',
            'description' => 'The payment method for subscription was changed to'.' '.str_replace('_',' ', $request->payment_method),
            'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
        ]);
        $user->notes()->save($note);
    }
}