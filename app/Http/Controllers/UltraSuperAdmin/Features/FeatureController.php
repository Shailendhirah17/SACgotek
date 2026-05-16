<?php

namespace App\Http\Controllers\UltraSuperAdmin\Features;

use App\Http\Controllers\Controller;
use App\Models\SchoolGroup;
use App\Models\SchoolGroupFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Feature Controller
 *
 * Ultra Super Admin can enable/disable features globally
 * and for entire school groups.
 */
class FeatureController extends Controller
{
    /**
     * Available platform features that can be toggled.
     */
    private $availableFeatures = [
        'exam_portal' => 'Advanced Exam Portal',
        'student_portal' => 'Advanced Student Portal',
        'hr_portal' => 'Advanced HR Portal',
        'chat' => 'Chat System',
        'smart_communication' => 'Smart Communication',
        'whatsapp' => 'WhatsApp Support',
        'two_factor_auth' => 'Two-Factor Authentication',
        'fees_module' => 'Fees Module',
        'wallet' => 'Digital Wallet',
        'role_permission' => 'Role & Permission',
        'menu_manage' => 'Menu Management',
        'ai_content' => 'AI Content Generation',
        'dashboard_analytics' => 'Dashboard Analytics',
        'zoom' => 'Zoom Integration',
        'jitsi' => 'Jitsi Meet',
        'behaviour_records' => 'Behaviour Records',
        'bulk_print' => 'Bulk Print',
        'front_office' => 'Smart Front Office',
        'transport' => 'Smart Transport',
        'absent_notification' => 'Student Absent Notification',
        'online_exam' => 'Online Exam',
        'download_center' => 'Download Center',
        'video_watch' => 'Video Watch',
        'automated_timetable' => 'Automated Timetable',
        'exam_plan' => 'Exam Plan',
        'lesson' => 'Lesson Planning',
    ];

    /**
     * Display feature management dashboard.
     */
    public function index()
    {
        $groups = SchoolGroup::with('features')->active()->get();

        return view('backEnd.ultraSuperAdmin.features.index', compact('groups'))
            ->with('availableFeatures', $this->availableFeatures);
    }

    /**
     * Toggle a feature for a specific school group.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:school_groups,id',
            'feature_key' => 'required|string',
        ]);

        $group = SchoolGroup::findOrFail($request->group_id);
        $featureKey = $request->feature_key;
        $featureName = $this->availableFeatures[$featureKey] ?? $featureKey;

        $feature = $group->features()->where('feature_key', $featureKey)->first();

        if ($feature) {
            $feature->update(['is_enabled' => !$feature->is_enabled]);
            $status = $feature->is_enabled ? 'enabled' : 'disabled';
        } else {
            $group->enableFeature($featureKey, $featureName);
            $status = 'enabled';
        }

        Log::channel('daily')->info("Feature {$status} by Ultra Super Admin", [
            'feature' => $featureKey,
            'group_id' => $group->id,
            'group_name' => $group->name,
            'status' => $status,
            'by' => Auth::guard('ultrasuperadmin')->id(),
        ]);

        return back()->with('message-success', "Feature '{$featureName}' {$status} for group '{$group->name}'.");
    }

    /**
     * Enable all features for a group.
     */
    public function enableAll(Request $request)
    {
        $group = SchoolGroup::findOrFail($request->group_id);

        foreach ($this->availableFeatures as $key => $name) {
            $group->enableFeature($key, $name);
        }

        return back()->with('message-success', "All features enabled for group '{$group->name}'.");
    }

    /**
     * Disable all features for a group.
     */
    public function disableAll(Request $request)
    {
        $group = SchoolGroup::findOrFail($request->group_id);

        $group->features()->update(['is_enabled' => false]);

        return back()->with('message-success', "All features disabled for group '{$group->name}'.");
    }
}
