<?php

namespace App\Http\Middleware;

use Closure;

class Subscribed
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
        if($request->user()->subscribed('cpd'))
            return $next($request);

        alert()->warning("Only CPD Subscribers can view that page", "CPD Subscribers Only");
        return redirect('/dashboard');
    }
}
