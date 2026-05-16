<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if ($guard === 'ultrasuperadmin' || Auth::guard('ultrasuperadmin')->check()) {
                return redirect()->route('ultrasuperadmin-dashboard');
            }
            
            if ($guard === 'superadmin' || Auth::guard('superadmin')->check()) {
                return redirect()->route('superadmin-dashboard');
            }

            return redirect()->route('admin-dashboard');
        }

        return $next($request);
    }
}
