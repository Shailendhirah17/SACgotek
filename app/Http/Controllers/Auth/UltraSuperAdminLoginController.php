<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UltraSuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Ultra Super Admin Login Controller
 *
 * Handles authentication for the Technosprint Master Control Layer.
 * Uses a completely separate 'ultrasuperadmin' guard for security isolation.
 */
class UltraSuperAdminLoginController extends Controller
{
    /**
     * Show the Ultra Super Admin login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // Redirect to dashboard if already authenticated
        if (Auth::guard('ultrasuperadmin')->check()) {
            return redirect()->route('ultrasuperadmin-dashboard');
        }

        return view('auth.ultra_super_admin_login');
    }

    /**
     * Handle a login request to the Ultra Super Admin panel.
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
            $user = UltraSuperAdmin::where($loginField, $request->username)->first();

            if (!$user) {
                Log::channel('daily')->warning('UltraSuperAdmin login failed: user not found', [
                    'username' => $request->username,
                    'ip' => $request->ip(),
                ]);

                throw ValidationException::withMessages([
                    'username' => ['The provided credentials do not match our records.'],
                ]);
            }

            // Check if account is active
            if (!$user->active_status) {
                Log::channel('daily')->warning('UltraSuperAdmin login failed: account inactive', [
                    'username' => $request->username,
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                ]);

                throw ValidationException::withMessages([
                    'username' => ['This account has been deactivated. Contact GOTEK Company.'],
                ]);
            }

            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                Log::channel('daily')->warning('UltraSuperAdmin login failed: invalid password', [
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

            if (Auth::guard('ultrasuperadmin')->attempt($credentials, $remember)) {
                // Session regeneration prevents fixation attacks
                $request->session()->regenerate();

                // Update last login info
                $user->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                ]);

                Log::channel('daily')->info('UltraSuperAdmin login successful', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'ip' => $request->ip(),
                ]);

                return redirect()->intended(route('ultrasuperadmin-dashboard'));
            }

            throw ValidationException::withMessages([
                'username' => ['Authentication failed. Please try again.'],
            ]);

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::channel('daily')->error('UltraSuperAdmin login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            return back()->withInput($request->only('username'))
                ->with('message-danger', 'An error occurred during login. Please try again.');
        }
    }

    /**
     * Log the Ultra Super Admin out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $ultraSuperAdmin = Auth::guard('ultrasuperadmin')->user();

        if ($ultraSuperAdmin) {
            Log::channel('daily')->info('UltraSuperAdmin logout', [
                'user_id' => $ultraSuperAdmin->id,
                'username' => $ultraSuperAdmin->username,
            ]);
        }

        Auth::guard('ultrasuperadmin')->logout();
        // Do NOT invalidate the entire session so the regular 'web' guard session stays active
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return redirect('/admin-dashboard')
            ->with('message-success', 'You have left the Master Control panel.');
    }

    /**
     * Create a default Ultra Super Admin user.
     *
     * This is a development utility and should be removed or disabled in production.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDefaultUltraSuperAdmin()
    {
        try {
            $existing = UltraSuperAdmin::where('email', 'gotek@gmail.com')->first();

            if ($existing) {
                // Update existing record to ensure correct credentials
                $existing->update([
                    'username' => 'gotek@gmail.com',
                    'password' => Hash::make('gotek@2026'),
                    'active_status' => true,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Ultra Super Admin credentials updated successfully.',
                ]);
            }

            $ultraSuperAdmin = UltraSuperAdmin::create([
                'username' => 'gotek@gmail.com',
                'email' => 'gotek@gmail.com',
                'password' => Hash::make('gotek@2026'),
                'full_name' => 'GOTEK Admin',
                'phone_number' => null,
                'active_status' => true,
                'role' => 'ultra_super_admin',
            ]);

            Log::channel('daily')->info('Default UltraSuperAdmin created', [
                'user_id' => $ultraSuperAdmin->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Default Ultra Super Admin created successfully.',
                'data' => [
                    'username' => 'gotek@gmail.com',
                    'email' => 'gotek@gmail.com',
                ],
            ]);

        } catch (\Exception $e) {
            Log::channel('daily')->error('Failed to create default UltraSuperAdmin', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create default Ultra Super Admin: ' . $e->getMessage(),
            ], 500);
        }
    }
}
