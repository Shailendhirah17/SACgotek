<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use App\Models\SuperAdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SuperAdminLoginController extends Controller
{
    /**
     * Show the SuperAdmin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Redirect to dashboard if already authenticated
        if (Auth::guard('superadmin')->check()) {
            return redirect()->route('superadmin-dashboard');
        }

        return view('auth.superadmin_login');
    }

    /**
     * Handle a login request to the SuperAdmin panel.
     *
     * Validates credentials, performs authentication via the superadmin guard,
     * regenerates session to prevent fixation attacks, and logs the event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // Find user by username or email
            $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = SuperAdmin::where($loginField, $request->username)->first();

            if (!$user) {
                Log::channel('daily')->warning('SuperAdmin login failed: user not found', [
                    'username' => $request->username,
                    'ip' => $request->ip(),
                ]);

                throw ValidationException::withMessages([
                    'username' => ['The provided credentials do not match our records.'],
                ]);
            }

            // Check if account is active
            if (!$user->active_status) {
                Log::channel('daily')->warning('SuperAdmin login failed: account inactive', [
                    'username' => $request->username,
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                ]);

                throw ValidationException::withMessages([
                    'username' => ['This account has been deactivated. Contact system administrator.'],
                ]);
            }

            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                Log::channel('daily')->warning('SuperAdmin login failed: invalid password', [
                    'username' => $request->username,
                    'ip' => $request->ip(),
                ]);

                throw ValidationException::withMessages([
                    'username' => ['The provided credentials do not match our records.'],
                ]);
            }

            // Attempt authentication
            $credentials = [
                $loginField => $request->username,
                'password' => $request->password,
            ];

            $remember = $request->boolean('remember');

            if (Auth::guard('superadmin')->attempt($credentials, $remember)) {
                // Session regeneration prevents fixation attacks
                $request->session()->regenerate();

                // Update last login info
                $user->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                ]);

                // Log successful login
                SuperAdminAuditLog::log(
                    $user->id,
                    'login',
                    'SuperAdmin',
                    $user->id,
                    "SuperAdmin '{$user->username}' logged in from IP: {$request->ip()}"
                );

                Log::channel('daily')->info('SuperAdmin login successful', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'ip' => $request->ip(),
                ]);

                return redirect()->intended(route('superadmin-dashboard'));
            }

            throw ValidationException::withMessages([
                'username' => ['Authentication failed. Please try again.'],
            ]);

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::channel('daily')->error('SuperAdmin login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            return back()->withInput($request->only('username'))
                ->with('message-danger', 'An error occurred during login. Please try again.');
        }
    }

    /**
     * Log the SuperAdmin out.
     *
     * Performs complete guard logout, session invalidation, and CSRF token
     * regeneration for security.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $superAdmin = Auth::guard('superadmin')->user();

        if ($superAdmin) {
            // Log the logout event
            SuperAdminAuditLog::log(
                $superAdmin->id,
                'logout',
                'SuperAdmin',
                $superAdmin->id,
                "SuperAdmin '{$superAdmin->username}' logged out"
            );

            Log::channel('daily')->info('SuperAdmin logout', [
                'user_id' => $superAdmin->id,
                'username' => $superAdmin->username,
            ]);
        }

        Auth::guard('superadmin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login')
            ->with('message-success', 'You have been logged out successfully.');
    }

    /**
     * Create a default SuperAdmin user.
     *
     * This is a development utility and should be removed or disabled in production.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDefaultSuperAdmin()
    {
        try {
            $existing = SuperAdmin::where('username', 'superadmin')->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Default SuperAdmin already exists.',
                ], 409);
            }

            $superAdmin = SuperAdmin::create([
                'username' => 'superadmin',
                'email' => 'superadmin@infixedu.com',
                'password' => Hash::make('SuperAdmin@123'),
                'full_name' => 'System Super Admin',
                'phone_number' => null,
                'active_status' => true,
                'role' => 'super_admin',
            ]);

            Log::channel('daily')->info('Default SuperAdmin created', [
                'user_id' => $superAdmin->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Default SuperAdmin created successfully.',
                'data' => [
                    'username' => 'superadmin',
                    'email' => 'superadmin@infixedu.com',
                    'password' => 'SuperAdmin@123 (change immediately)',
                ],
            ]);

        } catch (\Exception $e) {
            Log::channel('daily')->error('Failed to create default SuperAdmin', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create default SuperAdmin: ' . $e->getMessage(),
            ], 500);
        }
    }
}
