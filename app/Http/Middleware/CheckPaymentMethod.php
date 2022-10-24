<?php

namespace App\Http\Middleware;
use Closure;

class CheckPaymentMethod
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

//        if ($user->body && $user->subscribed('cpd') && $user->subscription('cpd')->plan->price != 0){
//
//            if ($user->subscription('cpd')->plan->interval == 'month' && $user->payment_method == 'eft'){
//                alert()->info('We noticed that you subscribed to one of our CPD packages, but not yet selected your preferred payment method, to ensure that your account stays up to date, select your payment method now.', 'Invalid Payment Method')->persistent('Update My Billing Information');
//                return redirect()->route('dashboard.billing.index');
//
//            }elseif(! $user->payment_method){
//                alert()->info('We noticed that you subscribed to one of our CPD packages, but not yet selected your preferred payment method, to ensure that your account stays up to date, select your payment method now.', 'Invalid Payment Method')->persistent('Update My Billing Information');
//                return redirect()->route('dashboard.billing.index');
//            }
//        }
        return $next($request);
    }
}
