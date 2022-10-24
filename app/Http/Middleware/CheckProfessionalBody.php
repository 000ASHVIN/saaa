<?php

namespace App\Http\Middleware;

use Closure;

class CheckProfessionalBody
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
        if (! $user->body){
            alert()->info('We notice that you have not selected your Professional Member Body (PMB). If you do not belong to a PMB, select "other".', 'Missing Information')->persistent('Update My Account');
            return redirect()->route('dashboard.edit');
        }else{
            return $next($request);
        }
    }
}
