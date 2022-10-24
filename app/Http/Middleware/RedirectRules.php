<?php

namespace App\Http\Middleware;

use Closure;
use App\Redirect;
use Illuminate\Support\Facades\Route;

class RedirectRules
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
        $redirect = Redirect::where('old_url',$request->path())->first();
        if($redirect) {
            return redirect($redirect->new_url, (int)$redirect->status);
        }
        return $next($request);
    }
}
