<?php

namespace App\Http\Controllers\UltraSuperAdmin\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\SchoolGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Subscription Controller
 *
 * Ultra Super Admin manages subscriptions at the school group level.
 * Super Admins have NO subscription control.
 */
class SubscriptionController extends Controller
{
    /**
     * Display subscription management dashboard.
     */
    public function index(Request $request)
    {
        $query = SchoolGroup::withCount('schools');

        if ($request->filled('plan')) {
            $query->where('subscription_plan', $request->plan);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->withActiveSubscription();
                    break;
                case 'expired':
                    $query->where('subscription_end', '<', now());
                    break;
                case 'expiring':
                    $query->where('subscription_end', '>=', now())
                          ->where('subscription_end', '<=', now()->addDays(30));
                    break;
            }
        }

        $groups = $query->orderBy('subscription_end', 'asc')->paginate(15);

        // Summary stats
        $totalGroups = SchoolGroup::count();
        $activeCount = SchoolGroup::withActiveSubscription()->count();
        $expiredCount = SchoolGroup::where('subscription_end', '<', now())->count();
        $expiringCount = SchoolGroup::where('subscription_end', '>=', now())
            ->where('subscription_end', '<=', now()->addDays(30))->count();

        return view('backEnd.ultraSuperAdmin.subscriptions.index', compact(
            'groups', 'totalGroups', 'activeCount', 'expiredCount', 'expiringCount'
        ));
    }

    /**
     * Update subscription for a school group.
     */
    public function update(Request $request, $id)
    {
        $group = SchoolGroup::findOrFail($id);

        $request->validate([
            'subscription_plan' => 'required|in:standard,professional,enterprise,custom',
            'subscription_start' => 'nullable|date',
            'subscription_end' => 'nullable|date|after_or_equal:subscription_start',
            'max_schools' => 'required|integer|min:1',
            'max_students_per_school' => 'required|integer|min:1',
        ]);

        try {
            $group->update([
                'subscription_plan' => $request->subscription_plan,
                'subscription_start' => $request->subscription_start,
                'subscription_end' => $request->subscription_end,
                'max_schools' => $request->max_schools,
                'max_students_per_school' => $request->max_students_per_school,
                'updated_by' => Auth::guard('ultrasuperadmin')->id(),
            ]);

            Log::channel('daily')->info('Subscription updated by Ultra Super Admin', [
                'group_id' => $group->id,
                'plan' => $request->subscription_plan,
                'updated_by' => Auth::guard('ultrasuperadmin')->id(),
            ]);

            return back()->with('message-success', "Subscription for '{$group->name}' updated successfully.");

        } catch (\Exception $e) {
            Log::error('Failed to update subscription', ['error' => $e->getMessage()]);
            return back()->with('message-danger', 'Failed to update subscription.');
        }
    }

    /**
     * Toggle subscription active status.
     */
    public function toggleStatus($id)
    {
        $group = SchoolGroup::findOrFail($id);
        $group->update(['active_status' => !$group->active_status]);

        $status = $group->active_status ? 'activated' : 'suspended';
        return back()->with('message-success', "Subscription for '{$group->name}' {$status}.");
    }
}
