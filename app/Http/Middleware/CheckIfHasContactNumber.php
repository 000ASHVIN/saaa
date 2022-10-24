<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfHasContactNumber
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if ($user && $user->requireContactDetails() && !$user->hasCell())
            return redirect()->route('dashboard');
        return $next($request);
    }
}
