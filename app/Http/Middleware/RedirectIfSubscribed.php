<?php

namespace App\Http\Middleware;

use App\Subscriptions\Models\Plan;
use Closure;

class RedirectIfSubscribed
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
        $plan = Plan::find($request->plan);

        if(! $request->user() ||
            ! $request->user()->subscribed('cpd') ||
            $request->user()->subscription('cpd')->plan->price <= 0
            || $plan->bf_price != '0'
            || $plan->last_minute != true
        ){
            return $next($request);
        }
        alert()->warning("You are already subscribed to CPD Subscription", "Already Subscribed");
        return redirect('/dashboard');
    }
}
