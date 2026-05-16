<?php

namespace App\Http\Controllers\SuperAdmin\Profile;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use App\Models\SuperAdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the profile page.
     */
    public function index()
    {
        $superAdmin = Auth::guard('superadmin')->user();
        $recentActivity = SuperAdminAuditLog::where('super_admin_id', $superAdmin->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('backEnd.superAdmin.profile.index', compact('superAdmin', 'recentActivity'));
    }

    /**
     * Update profile information.
     */
    public function update(Request $request)
    {
        $superAdmin = Auth::guard('superadmin')->user();

        $request->validate([
            'full_name' => 'required|string|max:200',
            'email' => 'required|email|max:200|unique:super_admins,email,' . $superAdmin->id,
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $oldValues = $superAdmin->only(['full_name', 'email', 'phone_number']);

            $updateData = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ];

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('public/uploads/superadmin/avatars');
                $updateData['avatar'] = str_replace('public/', '', $avatarPath);
            }

            $superAdmin->update($updateData);

            SuperAdminAuditLog::log(
                $superAdmin->id,
                'profile_updated',
                'SuperAdmin',
                $superAdmin->id,
                'Updated own profile',
                $oldValues,
                $superAdmin->only(['full_name', 'email', 'phone_number'])
            );

            return back()->with('message-success', 'Profile updated successfully.');

        } catch (\Exception $e) {
            Log::error('Profile update failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to update profile.');
        }
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $superAdmin = Auth::guard('superadmin')->user();

        if (!Hash::check($request->current_password, $superAdmin->password)) {
            return back()->with('message-danger', 'Current password is incorrect.');
        }

        try {
            $superAdmin->update([
                'password' => Hash::make($request->new_password),
            ]);

            SuperAdminAuditLog::log(
                $superAdmin->id,
                'password_changed',
                'SuperAdmin',
                $superAdmin->id,
                'Changed own password'
            );

            return back()->with('message-success', 'Password changed successfully.');

        } catch (\Exception $e) {
            Log::error('Password change failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to change password.');
        }
    }

    /**
     * View active sessions.
     */
    public function sessions()
    {
        $superAdmin = Auth::guard('superadmin')->user();

        // Get session info from database if using database sessions
        $sessions = collect();
        if (config('session.driver') === 'database') {
            try {
                $sessions = \DB::table('sessions')
                    ->where('user_id', $superAdmin->id)
                    ->orderBy('last_activity', 'desc')
                    ->get()
                    ->map(function ($session) {
                        $session->last_activity_formatted = \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
                        return $session;
                    });
            } catch (\Exception $e) {
                Log::warning('Failed to load sessions', ['error' => $e->getMessage()]);
            }
        }

        // Login history from audit logs
        $loginHistory = SuperAdminAuditLog::where('super_admin_id', $superAdmin->id)
            ->whereIn('action', ['login', 'logout'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('backEnd.superAdmin.profile.sessions', compact('superAdmin', 'sessions', 'loginHistory'));
    }

    /**
     * Terminate a session.
     */
    public function terminateSession(Request $request)
    {
        $request->validate(['session_id' => 'required|string']);

        try {
            \DB::table('sessions')->where('id', $request->session_id)->delete();
            return back()->with('message-success', 'Session terminated.');
        } catch (\Exception $e) {
            return back()->with('message-danger', 'Failed to terminate session.');
        }
    }
}
