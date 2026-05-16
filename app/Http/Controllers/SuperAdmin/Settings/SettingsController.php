<?php

namespace App\Http\Controllers\SuperAdmin\Settings;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminAuditLog;
use App\Models\SuperAdminSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $generalSettings = SuperAdminSetting::getGroup('general');
        $securitySettings = SuperAdminSetting::getGroup('security');
        $emailSettings = SuperAdminSetting::getGroup('email');
        $maintenanceSettings = SuperAdminSetting::getGroup('maintenance');
        $paymentSettings = SuperAdminSetting::getGroup('payment');

        return view('backEnd.superAdmin.settings.index', compact(
            'generalSettings',
            'securitySettings',
            'emailSettings',
            'maintenanceSettings',
            'paymentSettings'
        ));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        try {
            $currentAdmin = Auth::guard('superadmin')->user();
            $settings = $request->input('settings', []);

            foreach ($settings as $key => $value) {
                SuperAdminSetting::set($key, $value);
            }

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'updated',
                'Setting',
                null,
                'Updated system settings',
                null,
                $settings
            );

            return back()->with('message-success', 'Settings updated successfully.');

        } catch (\Exception $e) {
            Log::error('Settings update failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to update settings.');
        }
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            $currentAdmin = Auth::guard('superadmin')->user();
            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'cache_cleared',
                'System',
                null,
                'Cleared all application caches'
            );

            return back()->with('message-success', 'All caches cleared successfully.');

        } catch (\Exception $e) {
            Log::error('Cache clear failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to clear cache.');
        }
    }

    /**
     * Toggle maintenance mode.
     */
    public function toggleMaintenance(Request $request)
    {
        try {
            $currentAdmin = Auth::guard('superadmin')->user();
            $isDown = app()->isDownForMaintenance();

            if ($isDown) {
                Artisan::call('up');
                $status = 'disabled';
            } else {
                Artisan::call('down', ['--allow' => $request->ip()]);
                $status = 'enabled';
            }

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'maintenance_toggled',
                'System',
                null,
                "Maintenance mode {$status}"
            );

            return back()->with('message-success', "Maintenance mode {$status}.");

        } catch (\Exception $e) {
            Log::error('Maintenance mode toggle failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to toggle maintenance mode.');
        }
    }
}
