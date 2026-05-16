<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Protects SuperAdmin routes by verifying authentication via the
     * 'superadmin' guard. Initializes application context for multi-tenant
     * operations and provides comprehensive logging.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)

    {
        // Log request details for debugging
        Log::channel('daily')->info('SuperAdmin Middleware', [
            'url' => $request->url(),
            'method' => $request->method(),
            'session_id' => session()->getId(),
            'guard_check' => Auth::guard('superadmin')->check(),
        ]);

        // Check SuperAdmin authentication
        if (!Auth::guard('superadmin')->check()) {
            Log::channel('daily')->warning('SuperAdmin auth failed - redirecting to login', [
                'url' => $request->url(),
                'ip' => $request->ip(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('superadmin.login')
                ->with('message-danger', 'Please login to access the SuperAdmin panel.');
        }

        // Check if the user account is active
        $superAdmin = Auth::guard('superadmin')->user();
        if (!$superAdmin->active_status) {
            Auth::guard('superadmin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('superadmin.login')
                ->with('message-danger', 'Your account has been deactivated. Contact system administrator.');
        }

        Log::channel('daily')->info('SuperAdmin authenticated', [
            'user_id' => $superAdmin->id,
            'username' => $superAdmin->username,
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
            Log::channel('daily')->warning('SuperAdmin: Failed to initialize school context', [
                'error' => $e->getMessage(),
            ]);
        }

        // Share SuperAdmin data with all views
        view()->share('superAdmin', $superAdmin);

        // Security Isolation: If Super Admin is assigned to a specific group, 
        // bind that group ID to the session and application context.
        if ($superAdmin->school_group_id) {
            session()->put('assigned_school_group_id', $superAdmin->school_group_id);
            app()->instance('assigned_school_group_id', $superAdmin->school_group_id);
        } else {
            session()->forget('assigned_school_group_id');
        }

        return $next($request);
    }
}
