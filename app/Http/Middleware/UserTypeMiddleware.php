<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserTypeMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, string $type)
    {
        if (! Auth::check() || Auth::user()->user_type !== $type) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
