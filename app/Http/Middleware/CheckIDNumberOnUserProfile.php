<?php

namespace App\Http\Middleware;

use Closure;

class CheckIDNumberOnUserProfile
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

        if($user->id_number == null){
            alert()->error('Your ID number listed on your profile appears to be invalid, Please update your ID number', 'ID Required')->persistent('close');
        }
        return $next($request);
    }
}
