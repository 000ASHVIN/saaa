<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class CheckMobileVerification
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
        if (auth()->user()) {
            if(auth()->user()->is_cell_verified == 0){
                Cookie::queue('verify_cell', 'yes', 60*24);
                if(Cookie::has('verify_cell_form')){
                    Cookie::queue(Cookie::forget('verify_cell_form'));
                }
            }else{
                Cookie::queue(Cookie::forget('verify_cell'));
                Cookie::queue(Cookie::forget('verify_cell_form'));
            }
            
        }else{
            Cookie::queue(Cookie::forget('verify_cell'));
            Cookie::queue(Cookie::forget('verify_cell_form'));
        }
        return $next($request);
    }
}
