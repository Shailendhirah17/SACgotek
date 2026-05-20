<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * RoleLevelEnforcer Middleware
 *
 * Prevents privilege escalation between hierarchy tiers.
 * Attach to routes via: ->middleware('role.level:1') for Level 1 only, etc.
 */
class RoleLevelEnforcer
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role.level:1')   — only Level 1 (Technosprint)
     *        middleware('role.level:1,2') — Level 1 or Level 2
     *        middleware('role.level:2')   — Level 2 (Org Head) only
     */
    public function handle(Request $request, Closure $next, ...$levels): mixed
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        $userLevel = $this->resolveLevel($user);

        // If no level requirement defined, just pass through
        if (empty($levels)) {
            return $next($request);
        }

        // Check if user's level is in the allowed list
        foreach ($levels as $level) {
            if ($userLevel === (int) $level) {
                return $next($request);
            }
        }

        // Special guard: prevent org heads from accessing other orgs' schools
        if ($userLevel === 2 && $request->route('school_id')) {
            $this->enforceOrgBoundary($user, $request->route('school_id'));
        }

        abort(403, 'You do not have permission to access this resource based on your admin level.');
    }

    /**
     * Resolve the user's admin level from DB column or fallback logic
     */
    private function resolveLevel($user): int
    {
        // If admin_level column exists, use it
        if (isset($user->admin_level) && $user->admin_level > 0) {
            return (int) $user->admin_level;
        }

        // Fallback: Legacy SaaS super admin
        if (($user->is_administrator === 'yes' && $user->role_id === 1) || $user->role_id === 10) {
            return 1;
        }

        // Fallback: If user has a school_group_id set, they are Level 2
        if (!empty($user->school_group_id)) {
            return 2;
        }

        // Default: Level 3 (School Admin) or lower
        return 3;
    }

    /**
     * Ensure an Org Head cannot access schools outside their group
     */
    private function enforceOrgBoundary($user, $schoolId): void
    {
        if (!$user->school_group_id) {
            return;
        }

        $belongsToGroup = DB::table('sm_schools')
            ->where('id', $schoolId)
            ->where('school_group_id', $user->school_group_id)
            ->exists();

        if (!$belongsToGroup) {
            abort(403, 'This school does not belong to your organization.');
        }
    }
}
