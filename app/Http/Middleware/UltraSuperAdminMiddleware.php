<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UltraSuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Protects Ultra Super Admin routes by verifying authentication via the
     * 'ultrasuperadmin' guard. This middleware provides complete isolation
     * from the SuperAdmin and standard user authentication systems.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Log request details for debugging
        Log::channel('daily')->info('UltraSuperAdmin Middleware', [
            'url' => $request->url(),
            'method' => $request->method(),
            'session_id' => session()->getId(),
            'guard_check' => Auth::guard('ultrasuperadmin')->check(),
        ]);

        // Check Ultra Super Admin authentication
        if (!Auth::guard('ultrasuperadmin')->check()) {
            Log::channel('daily')->warning('UltraSuperAdmin auth failed - redirecting to login', [
                'url' => $request->url(),
                'ip' => $request->ip(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('ultrasuperadmin.login')
                ->with('message-danger', 'Please login to access the Ultra Super Admin panel.');
        }

        // Check if the user account is active
        $ultraSuperAdmin = Auth::guard('ultrasuperadmin')->user();
        if (!$ultraSuperAdmin->active_status) {
            Auth::guard('ultrasuperadmin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('ultrasuperadmin.login')
                ->with('message-danger', 'Your account has been deactivated. Contact Technosprint Info Solutions.');
        }

        Log::channel('daily')->info('UltraSuperAdmin authenticated', [
            'user_id' => $ultraSuperAdmin->id,
            'username' => $ultraSuperAdmin->username,
        ]);

        // Initialize application context for multi-tenant operations
        try {
            if (function_exists('SaasSchool')) {
                $school = SaasSchool();
                if ($school) {
                    app()->instance('school', $school);
                }
            }
        } catch (\Exception $e) {
            Log::channel('daily')->warning('UltraSuperAdmin: Failed to initialize school context', [
                'error' => $e->getMessage(),
            ]);
        }

        // Share Ultra Super Admin data with all views
        view()->share('ultraSuperAdmin', $ultraSuperAdmin);

        return $next($request);
    }
}
