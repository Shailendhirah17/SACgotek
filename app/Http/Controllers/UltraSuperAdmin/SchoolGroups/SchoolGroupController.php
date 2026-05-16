<?php

namespace App\Http\Controllers\UltraSuperAdmin\SchoolGroups;

use App\Http\Controllers\Controller;
use App\Models\SchoolGroup;
use App\SmSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * School Group Controller
 *
 * Full CRUD operations for managing school groups.
 * School groups are the organizational container between Ultra Super Admin and schools.
 */
class SchoolGroupController extends Controller
{
    /**
     * Display a listing of school groups.
     */
    public function index(Request $request)
    {
        $query = SchoolGroup::withCount('schools');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('billing_contact_email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('active_status', $request->status === 'active');
        }

        // Filter by plan
        if ($request->filled('plan')) {
            $query->where('subscription_plan', $request->plan);
        }

        $schoolGroups = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('backEnd.ultraSuperAdmin.school_groups.index', compact('schoolGroups'));
    }

    /**
     * Show the form for creating a new school group.
     */
    public function create()
    {
        return view('backEnd.ultraSuperAdmin.school_groups.create');
    }

    /**
     * Store a newly created school group.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:school_groups,code',
            'description' => 'nullable|string',
            'subscription_plan' => 'required|in:standard,professional,enterprise,custom',
            'max_schools' => 'required|integer|min:1',
            'max_students_per_school' => 'required|integer|min:1',
            'subscription_start' => 'nullable|date',
            'subscription_end' => 'nullable|date|after_or_equal:subscription_start',
            'billing_contact_name' => 'nullable|string|max:255',
            'billing_contact_email' => 'nullable|email|max:255',
            'billing_address' => 'nullable|string',
            'billing_phone' => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            $group = SchoolGroup::create([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'active_status' => true,
                'subscription_plan' => $request->subscription_plan,
                'subscription_start' => $request->subscription_start,
                'subscription_end' => $request->subscription_end,
                'max_schools' => $request->max_schools,
                'max_students_per_school' => $request->max_students_per_school,
                'license_key' => Str::uuid()->toString(),
                'billing_contact_name' => $request->billing_contact_name,
                'billing_contact_email' => $request->billing_contact_email,
                'billing_address' => $request->billing_address,
                'billing_phone' => $request->billing_phone,
                'created_by' => Auth::guard('ultrasuperadmin')->id(),
            ]);

            DB::commit();

            Log::channel('daily')->info('School Group created', [
                'group_id' => $group->id,
                'name' => $group->name,
                'created_by' => Auth::guard('ultrasuperadmin')->id(),
            ]);

            return redirect()->route('ultrasuperadmin.school-groups.index')
                ->with('message-success', "School Group '{$group->name}' created successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create School Group', ['error' => $e->getMessage()]);
            return back()->withInput()->with('message-danger', 'Failed to create school group. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified school group.
     */
    public function show($id)
    {
        $group = SchoolGroup::with(['schools', 'features'])->findOrFail($id);
        $availableSchools = SmSchool::whereNull('school_group_id')
            ->orWhere('school_group_id', 0)
            ->get();

        return view('backEnd.ultraSuperAdmin.school_groups.show', compact('group', 'availableSchools'));
    }

    /**
     * Show the form for editing the specified school group.
     */
    public function edit($id)
    {
        $group = SchoolGroup::findOrFail($id);
        return view('backEnd.ultraSuperAdmin.school_groups.edit', compact('group'));
    }

    /**
     * Update the specified school group.
     */
    public function update(Request $request, $id)
    {
        $group = SchoolGroup::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:school_groups,code,' . $id,
            'description' => 'nullable|string',
            'subscription_plan' => 'required|in:standard,professional,enterprise,custom',
            'max_schools' => 'required|integer|min:1',
            'max_students_per_school' => 'required|integer|min:1',
            'subscription_start' => 'nullable|date',
            'subscription_end' => 'nullable|date|after_or_equal:subscription_start',
            'billing_contact_name' => 'nullable|string|max:255',
            'billing_contact_email' => 'nullable|email|max:255',
            'billing_address' => 'nullable|string',
            'billing_phone' => 'nullable|string|max:20',
        ]);

        try {
            $group->update([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'subscription_plan' => $request->subscription_plan,
                'subscription_start' => $request->subscription_start,
                'subscription_end' => $request->subscription_end,
                'max_schools' => $request->max_schools,
                'max_students_per_school' => $request->max_students_per_school,
                'billing_contact_name' => $request->billing_contact_name,
                'billing_contact_email' => $request->billing_contact_email,
                'billing_address' => $request->billing_address,
                'billing_phone' => $request->billing_phone,
                'updated_by' => Auth::guard('ultrasuperadmin')->id(),
            ]);

            return redirect()->route('ultrasuperadmin.school-groups.index')
                ->with('message-success', "School Group '{$group->name}' updated successfully.");

        } catch (\Exception $e) {
            Log::error('Failed to update School Group', ['error' => $e->getMessage()]);
            return back()->withInput()->with('message-danger', 'Failed to update school group.');
        }
    }

    /**
     * Remove the specified school group.
     */
    public function destroy($id)
    {
        $group = SchoolGroup::findOrFail($id);

        if ($group->code === 'DEFAULT') {
            return back()->with('message-danger', 'Cannot delete the default school group.');
        }

        if ($group->schools()->count() > 0) {
            return back()->with('message-danger', 'Cannot delete a group that contains schools. Remove all schools first.');
        }

        $group->features()->delete();
        $group->delete();

        return redirect()->route('ultrasuperadmin.school-groups.index')
            ->with('message-success', "School Group '{$group->name}' deleted successfully.");
    }

    /**
     * Toggle the active status of a school group.
     */
    public function toggleStatus(Request $request)
    {
        $group = SchoolGroup::findOrFail($request->id);
        $group->update(['active_status' => !$group->active_status]);

        $status = $group->active_status ? 'activated' : 'deactivated';
        return back()->with('message-success', "School Group '{$group->name}' {$status} successfully.");
    }

    /**
     * Assign a school to a group.
     */
    public function assignSchool(Request $request, $groupId)
    {
        $group = SchoolGroup::findOrFail($groupId);

        if (!$group->canAddSchool()) {
            return back()->with('message-danger', "This group has reached its maximum school limit ({$group->max_schools}).");
        }

        $school = SmSchool::findOrFail($request->school_id);
        $school->update(['school_group_id' => $groupId]);

        return back()->with('message-success', "School '{$school->school_name}' assigned to group '{$group->name}'.");
    }

    /**
     * Remove a school from a group.
     */
    public function removeSchool(Request $request, $groupId)
    {
        $school = SmSchool::findOrFail($request->school_id);
        $school->update(['school_group_id' => null]);

        return back()->with('message-success', "School removed from group successfully.");
    }
}
