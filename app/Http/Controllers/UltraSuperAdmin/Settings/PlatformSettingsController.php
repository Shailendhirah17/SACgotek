<?php

namespace App\Http\Controllers\UltraSuperAdmin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

/**
 * Platform Settings Controller
 *
 * Global platform-level settings managed by Ultra Super Admin.
 */
class PlatformSettingsController extends Controller
{
    /**
     * Display platform settings.
     */
    public function index()
    {
        $settings = [
            'platform_name' => config('app.name', 'TISEDU'),
            'platform_url' => config('app.url'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'mail_driver' => config('mail.default'),
            'queue_driver' => config('queue.default'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
        ];

        return view('backEnd.ultraSuperAdmin.settings.index', compact('settings'));
    }

    /**
     * Clear all caches.
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            Log::channel('daily')->info('All caches cleared by Ultra Super Admin');

            return back()->with('message-success', 'All caches cleared successfully.');
        } catch (\Exception $e) {
            return back()->with('message-danger', 'Failed to clear caches: ' . $e->getMessage());
        }
    }

    /**
     * Toggle maintenance mode.
     */
    public function toggleMaintenance(Request $request)
    {
        try {
            if (app()->isDownForMaintenance()) {
                Artisan::call('up');
                $message = 'Platform is now LIVE.';
            } else {
                Artisan::call('down', ['--allow' => $request->ip()]);
                $message = 'Platform is now in MAINTENANCE mode.';
            }

            Log::channel('daily')->info($message);
            return back()->with('message-success', $message);
        } catch (\Exception $e) {
            return back()->with('message-danger', 'Failed to toggle maintenance mode.');
        }
    }
}
