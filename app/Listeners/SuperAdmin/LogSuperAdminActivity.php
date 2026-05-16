<?php

namespace App\Listeners\SuperAdmin;

use App\Models\SuperAdminAuditLog;
use Illuminate\Support\Facades\Log;

class LogSuperAdminActivity
{
    /**
     * Handle SuperAdmin login events.
     */
    public function handleLogin($event): void
    {
        try {
            SuperAdminAuditLog::log(
                $event->superAdmin->id,
                'login',
                'SuperAdmin',
                $event->superAdmin->id,
                "SuperAdmin '{$event->superAdmin->username}' logged in from IP: {$event->ipAddress}"
            );
        } catch (\Exception $e) {
            Log::error('Failed to log SuperAdmin login', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle SuperAdmin logout events.
     */
    public function handleLogout($event): void
    {
        try {
            SuperAdminAuditLog::log(
                $event->superAdmin->id,
                'logout',
                'SuperAdmin',
                $event->superAdmin->id,
                "SuperAdmin '{$event->superAdmin->username}' logged out"
            );
        } catch (\Exception $e) {
            Log::error('Failed to log SuperAdmin logout', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle SuperAdmin user creation events.
     */
    public function handleUserCreated($event): void
    {
        try {
            SuperAdminAuditLog::log(
                $event->superAdmin->id,
                'created',
                'SuperAdmin',
                $event->createdUser->id,
                "Created SuperAdmin user: {$event->createdUser->username}",
                null,
                $event->createdUser->only(['username', 'email', 'full_name', 'role', 'active_status'])
            );
        } catch (\Exception $e) {
            Log::error('Failed to log user creation', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle SuperAdmin user update events.
     */
    public function handleUserUpdated($event): void
    {
        try {
            SuperAdminAuditLog::log(
                $event->superAdmin->id,
                'updated',
                'SuperAdmin',
                $event->updatedUser->id,
                "Updated SuperAdmin user: {$event->updatedUser->username}",
                $event->oldValues,
                $event->newValues
            );
        } catch (\Exception $e) {
            Log::error('Failed to log user update', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle SuperAdmin user deletion events.
     */
    public function handleUserDeleted($event): void
    {
        try {
            SuperAdminAuditLog::log(
                $event->superAdmin->id,
                'deleted',
                'SuperAdmin',
                null,
                "Deleted SuperAdmin user: " . ($event->deletedUserData['username'] ?? 'unknown'),
                $event->deletedUserData,
                null
            );
        } catch (\Exception $e) {
            Log::error('Failed to log user deletion', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle SuperAdmin user status change events.
     */
    public function handleUserStatusChanged($event): void
    {
        try {
            $fromStatus = $event->oldStatus ? 'active' : 'inactive';
            $toStatus = $event->newStatus ? 'active' : 'inactive';

            SuperAdminAuditLog::log(
                $event->superAdmin->id,
                'status_changed',
                'SuperAdmin',
                $event->targetUser->id,
                "Changed status of '{$event->targetUser->username}' from {$fromStatus} to {$toStatus}",
                ['active_status' => $event->oldStatus],
                ['active_status' => $event->newStatus]
            );
        } catch (\Exception $e) {
            Log::error('Failed to log user status change', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle school creation events.
     */
    public function handleSchoolCreated($event): void
    {
        try {
            SuperAdminAuditLog::log(
                $event->superAdmin->id,
                'created',
                'School',
                $event->school->id ?? null,
                "Created school: " . ($event->school->school_name ?? 'N/A'),
                null,
                is_array($event->school) ? $event->school : $event->school->toArray()
            );
        } catch (\Exception $e) {
            Log::error('Failed to log school creation', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle school deletion events.
     */
    public function handleSchoolDeleted($event): void
    {
        try {
            SuperAdminAuditLog::log(
                $event->superAdmin->id,
                'deleted',
                'School',
                null,
                "Deleted school: " . ($event->schoolData['school_name'] ?? 'unknown'),
                $event->schoolData,
                null
            );
        } catch (\Exception $e) {
            Log::error('Failed to log school deletion', ['error' => $e->getMessage()]);
        }
    }
}
