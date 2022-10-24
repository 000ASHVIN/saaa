<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfRoleIsSales
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->hasRole('sales')) {
            alert()->error('You do not have access rights to'. ' '. $request->path(), 'Access Denied');
            return redirect()->route('home');
        }
        return $next($request);
    }
}