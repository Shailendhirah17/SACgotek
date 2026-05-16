<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * SchoolScope — 3-Level Hierarchy Access Control
 *
 * Level 1 (admin_level=1): Ultra Super Admin (Technosprint)
 *   -> NO filter. Full global access to all records.
 *
 * Level 2 (admin_level=2): Organization Head (Super Admin)
 *   -> Filters by all school_ids belonging to their school_group_id.
 *
 * Level 3 (admin_level=3) or default school users:
 *   -> Filters by exact school_id.
 */
class SchoolScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $table = $model->getTable();

        if (!Auth::check()) {
            return;
        }

        $user = auth()->user();

        // --- Level 1: Ultra Super Admin (Technosprint) ---
        // Global access — no scope applied
        if ($user->admin_level == 1 || 
            ($user->is_administrator === 'yes' && $user->role_id === 1 && moduleStatusCheck('Saas') === true && Session::get('isSchoolAdmin') === false)
        ) {
            return;
        }

        // --- Level 2: Organization Head (Super Admin) ---
        // Scoped to all schools within their assigned school_group_id
        if ($user->admin_level == 2 && $user->school_group_id) {
            $schoolIds = DB::table('sm_schools')
                ->where('school_group_id', $user->school_group_id)
                ->pluck('id')
                ->toArray();

            if (!empty($schoolIds)) {
                $builder->whereIn($table . '.school_id', $schoolIds);
            }
            return;
        }

        // --- Legacy SaaS check (session-based group) ---
        if (session()->has('assigned_school_group_id')) {
            $schoolIds = DB::table('sm_schools')
                ->where('school_group_id', session('assigned_school_group_id'))
                ->pluck('id')
                ->toArray();

            if (!empty($schoolIds)) {
                $builder->whereIn($table . '.school_id', $schoolIds);
            }
            return;
        }

        // --- Level 3 / Default: School Admin or Staff/Student ---
        // Scoped to their exact school_id with strict validation
        $requestedSchoolId = request('school_id');

        if ($requestedSchoolId) {
            // Validate that the user actually has access to this school_id
            $allowed = false;
            
            if ($user->admin_level == 2) {
                // Org Head can access any school in their group
                $allowed = DB::table('sm_schools')
                    ->where('id', $requestedSchoolId)
                    ->where('school_group_id', $user->school_group_id)
                    ->exists();
            } else {
                // School level users can only access their own school
                $allowed = ($requestedSchoolId == $user->school_id);
            }

            if ($allowed) {
                $builder->where($table . '.school_id', $requestedSchoolId);
            } else {
                // Fraudulent attempt or error — force their assigned school_id
                $builder->where($table . '.school_id', $user->school_id);
            }
        } elseif (app()->bound('school')) {
            $builder->where($table . '.school_id', app('school')->id);
        } else {
            $builder->where($table . '.school_id', $user->school_id);
        }
    }
}
