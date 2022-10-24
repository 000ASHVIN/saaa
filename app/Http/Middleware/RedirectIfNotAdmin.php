<?php

namespace App\Http\Middleware;

use App\Users\User;
use Closure;
use Illuminate\Contracts\Auth\Access\Gate;

class RedirectIfNotAdmin
{

    public function handle($request, Closure $next)
    {
        if (auth()->user()){
        $user = auth()->user();
        if ($user->is('admin')) { // you can pass an id or slug
            // or alternatively $user->hasRole('admin')
            return $next($request);
        }else
            alert()->error('The page you requested could not be found.', 'Error');
            return redirect()->route('home');
        }
    }
}
