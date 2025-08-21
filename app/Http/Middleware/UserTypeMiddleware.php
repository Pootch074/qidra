<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserTypeMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$types)
    {
        if (!Auth::check() || !in_array(Auth::user()->user_type, $types)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
