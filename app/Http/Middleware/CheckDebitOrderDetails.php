<?php

namespace App\Http\Middleware;

use Closure;

class CheckDebitOrderDetails
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if (! $user->payment_arrangement){
//            if ($user->body && $user->subscribed('cpd') && $user->subscription('cpd')->plan->price != 0){
//                if ($user->payment_method == 'debit_order' && $user->debit == null){
//                    alert()->error('You have not supplied any debit order details for your account, please enter your details below', 'Invalid Debit Order Details')->persistent('close');
//                    return redirect()->route('dashboard.billing.index');
//
//                }elseif ($user->payment_method == 'credit_card' && $user->cards->count() == 0){
//                    alert()->error('You have not supplied any credit card details for your account, please enter your details below', 'Invalid Credit Card Details')->persistent('close');
//                    return redirect()->route('dashboard.billing.index');
//                }
//            }
        }
        return $next($request);
    }
}
