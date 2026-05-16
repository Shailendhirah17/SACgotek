<?php

namespace App\Http\Controllers\SuperAdmin\Modules;

use App\Http\Controllers\Controller;
use App\Models\SuperAdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ModuleController extends Controller
{
    /**
     * Display the module management page.
     */
    public function index()
    {
        $modulesFile = base_path('modules_statuses.json');
        $modules = [];

        if (File::exists($modulesFile)) {
            $modules = json_decode(File::get($modulesFile), true) ?? [];
        }

        return view('backEnd.superAdmin.modules.index', compact('modules'));
    }

    /**
     * Toggle a module on or off.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
        ]);

        try {
            $modulesFile = base_path('modules_statuses.json');
            $modules = [];

            if (File::exists($modulesFile)) {
                $modules = json_decode(File::get($modulesFile), true) ?? [];
            }

            $moduleName = $request->module;

            if (!array_key_exists($moduleName, $modules)) {
                return back()->with('message-danger', "Module '{$moduleName}' not found.");
            }

            $oldStatus = $modules[$moduleName];
            $modules[$moduleName] = !$modules[$moduleName];

            File::put($modulesFile, json_encode($modules, JSON_PRETTY_PRINT));

            $currentAdmin = Auth::guard('superadmin')->user();
            $newStatus = $modules[$moduleName] ? 'enabled' : 'disabled';

            SuperAdminAuditLog::log(
                $currentAdmin->id,
                'module_toggled',
                'Module',
                null,
                "Module '{$moduleName}' {$newStatus}",
                ['status' => $oldStatus],
                ['status' => $modules[$moduleName]]
            );

            return back()->with('message-success', "Module '{$moduleName}' {$newStatus} successfully.");

        } catch (\Exception $e) {
            Log::error('Module toggle failed', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to toggle module.');
        }
    }
}
