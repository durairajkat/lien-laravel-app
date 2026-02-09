<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Class Admin for Admin Middleware
 * @package App\Http\Middleware
 */
class Admin
{
    /**
     * Handle an incoming request.
     * Check is thw Authenticate user is admin or not.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }
        return redirect()->route('admin.login');
    }
}
