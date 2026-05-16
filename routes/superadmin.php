<?php

/*
|--------------------------------------------------------------------------
| SuperAdmin Routes
|--------------------------------------------------------------------------
|
| These routes handle all SuperAdmin panel functionality including
| authentication, dashboard, user management, school management,
| subscriptions, settings, reports, analytics, modules, and audit logs.
|
| All protected routes use the 'superadmin' middleware which checks
| Auth::guard('superadmin') for isolated authentication.
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SuperAdminLoginController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\Users\UsersController;
use App\Http\Controllers\SuperAdmin\SchoolManagement\SchoolController;
use App\Http\Controllers\SuperAdmin\Subscription\SubscriptionController;
use App\Http\Controllers\SuperAdmin\Settings\SettingsController;
use App\Http\Controllers\SuperAdmin\Reports\ReportsController;
use App\Http\Controllers\SuperAdmin\Analytics\AnalyticsController;
use App\Http\Controllers\SuperAdmin\Modules\ModuleController;
use App\Http\Controllers\SuperAdmin\AuditLog\AuditLogController;

// ============================================
// Public Geographic AJAX (Bypasses security for dropdown stability)
// ============================================
Route::get('/geo/ajax-get-cities', [SchoolController::class, 'ajaxGetCities'])->name('public.ajax-get-cities');

/*
|--------------------------------------------------------------------------
| Public SuperAdmin Routes (Login/Logout)
|--------------------------------------------------------------------------
*/

Route::get('/superadmin/login', [SuperAdminLoginController::class, 'showLoginForm'])->name('superadmin.login');
Route::post('/superadmin/login', [SuperAdminLoginController::class, 'login'])->name('superadmin.login.submit');
Route::post('/superadmin/logout', [SuperAdminLoginController::class, 'logout'])->name('superadmin.logout');
Route::get('/superadmin/create-default', [SuperAdminLoginController::class, 'createDefaultSuperAdmin'])->name('superadmin.create-default');

/*
|--------------------------------------------------------------------------
| Protected SuperAdmin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('superadmin')->middleware(['superadmin'])->group(function () {

    // ============================================
    // Dashboard
    // ============================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('superadmin-dashboard');
    Route::get('/system-health', [DashboardController::class, 'systemHealth'])->name('superadmin.system-health');

    Route::get('/ajax-get-cities', [SchoolController::class, 'ajaxGetCities'])->name('superadmin.ajax-get-cities');

    // ============================================
    // School Management
    // ============================================
    Route::prefix('school-management')->group(function () {
        Route::get('/schools', [SchoolController::class, 'schoolList'])->name('superadmin.school-list');
        Route::get('/schools/create', [SchoolController::class, 'create'])->name('superadmin.school.create');
        Route::post('/schools', [SchoolController::class, 'store'])->name('superadmin.school.store');
        Route::get('/schools/{id}', [SchoolController::class, 'show'])->name('superadmin.school.show')->where('id', '[0-9]+');
        Route::get('/schools/{id}/edit', [SchoolController::class, 'edit'])->name('superadmin.school.edit')->where('id', '[0-9]+');
        Route::put('/schools/{id}', [SchoolController::class, 'update'])->name('superadmin.school.update')->where('id', '[0-9]+');
        Route::delete('/schools/{id}', [SchoolController::class, 'destroy'])->name('superadmin.school.destroy')->where('id', '[0-9]+');
        Route::post('/schools/toggle-status', [SchoolController::class, 'toggleStatus'])->name('superadmin.school.toggle-status');
        Route::post('/schools/bulk-action', [SchoolController::class, 'bulkAction'])->name('superadmin.school.bulk-action');
    });

    // ============================================
    // User Management
    // ============================================
    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('superadmin.users.index');
        Route::get('/create', [UsersController::class, 'create'])->name('superadmin.users.create');
        Route::post('/', [UsersController::class, 'store'])->name('superadmin.users.store');
        Route::get('/{id}/edit', [UsersController::class, 'edit'])->name('superadmin.users.edit')->where('id', '[0-9]+');
        Route::put('/{id}', [UsersController::class, 'update'])->name('superadmin.users.update')->where('id', '[0-9]+');
        Route::delete('/{id}', [UsersController::class, 'destroy'])->name('superadmin.users.destroy')->where('id', '[0-9]+');
        Route::post('/{id}/toggle-status', [UsersController::class, 'toggleStatus'])->name('superadmin.users.toggle-status')->where('id', '[0-9]+');
    });

    // ============================================
    // School Administrators (The Principals)
    // ============================================
    Route::prefix('school-admins')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\Users\SchoolAdminController::class, 'index'])->name('superadmin.school-admins.index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\Users\SchoolAdminController::class, 'create'])->name('superadmin.school-admins.create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\Users\SchoolAdminController::class, 'store'])->name('superadmin.school-admins.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\SuperAdmin\Users\SchoolAdminController::class, 'edit'])->name('superadmin.school-admins.edit')->where('id', '[0-9]+');
        Route::put('/{id}', [\App\Http\Controllers\SuperAdmin\Users\SchoolAdminController::class, 'update'])->name('superadmin.school-admins.update')->where('id', '[0-9]+');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\Users\SchoolAdminController::class, 'destroy'])->name('superadmin.school-admins.destroy')->where('id', '[0-9]+');
    });

    // Tenant Users (Students, Staff, Parents)
    // ============================================
    Route::prefix('tenant-users')->group(function () {
        Route::get('/students', [\App\Http\Controllers\SuperAdmin\Users\TenantUsersController::class, 'students'])->name('superadmin.tenant.students');
        Route::get('/staff', [\App\Http\Controllers\SuperAdmin\Users\TenantUsersController::class, 'staff'])->name('superadmin.tenant.staff');
        Route::get('/parents', [\App\Http\Controllers\SuperAdmin\Users\TenantUsersController::class, 'parents'])->name('superadmin.tenant.parents');
    });

    // Subscription Management
    // ============================================
    Route::prefix('subscriptions')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('superadmin.subscriptions.index');
        Route::put('/{id}', [SubscriptionController::class, 'update'])->name('superadmin.subscriptions.update')->where('id', '[0-9]+');
        Route::post('/{id}/toggle', [SubscriptionController::class, 'toggleStatus'])->name('superadmin.subscriptions.toggle')->where('id', '[0-9]+');
        
        // Coupons
        Route::get('/coupons', [\App\Http\Controllers\SuperAdmin\Subscription\CouponController::class, 'index'])->name('superadmin.subscriptions.coupons');
        Route::post('/coupons', [\App\Http\Controllers\SuperAdmin\Subscription\CouponController::class, 'store'])->name('superadmin.subscriptions.coupons.store');
        Route::post('/coupons/{id}/toggle', [\App\Http\Controllers\SuperAdmin\Subscription\CouponController::class, 'toggleStatus'])->name('superadmin.subscriptions.coupons.toggle');
        Route::delete('/coupons/{id}', [\App\Http\Controllers\SuperAdmin\Subscription\CouponController::class, 'destroy'])->name('superadmin.subscriptions.coupons.destroy');
    });

    // ============================================

    // Settings
    // ============================================
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('superadmin.settings.index');
        Route::post('/update', [SettingsController::class, 'update'])->name('superadmin.settings.update');
        Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('superadmin.settings.clear-cache');
        Route::post('/toggle-maintenance', [SettingsController::class, 'toggleMaintenance'])->name('superadmin.settings.toggle-maintenance');
    });

    // ============================================
    // Reports
    // ============================================
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('superadmin.reports.index');
        Route::get('/schools', [ReportsController::class, 'schoolReport'])->name('superadmin.reports.schools');
        Route::get('/schools/export', [ReportsController::class, 'exportSchoolReport'])->name('superadmin.reports.schools.export');
    });

    // ============================================
    // Analytics
    // ============================================
    Route::prefix('analytics')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('superadmin.analytics.index');
    });

    // ============================================
    // Module Management
    // ============================================
    Route::prefix('modules')->group(function () {
        Route::get('/', [ModuleController::class, 'index'])->name('superadmin.modules.index');
        Route::post('/toggle', [ModuleController::class, 'toggle'])->name('superadmin.modules.toggle');
    });

    // ============================================
    // Audit Logs
    // ============================================
    Route::prefix('audit-logs')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('superadmin.audit.index');
        Route::get('/export', [AuditLogController::class, 'export'])->name('superadmin.audit.export');
    });

    // ============================================
    // Profile Management
    // ============================================
    Route::prefix('profile')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\Profile\ProfileController::class, 'index'])->name('superadmin.profile.index');
        Route::post('/update', [\App\Http\Controllers\SuperAdmin\Profile\ProfileController::class, 'update'])->name('superadmin.profile.update');
        Route::post('/change-password', [\App\Http\Controllers\SuperAdmin\Profile\ProfileController::class, 'changePassword'])->name('superadmin.profile.change-password');
        Route::get('/sessions', [\App\Http\Controllers\SuperAdmin\Profile\ProfileController::class, 'sessions'])->name('superadmin.profile.sessions');
        Route::post('/sessions/terminate', [\App\Http\Controllers\SuperAdmin\Profile\ProfileController::class, 'terminateSession'])->name('superadmin.profile.terminate-session');
    });

    // ============================================
    // Communicate (Email, SMS, Notices)
    // ============================================
    Route::prefix('communicate')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\Communicate\CommunicateController::class, 'index'])->name('superadmin.communicate.index');
        Route::post('/send-email', [\App\Http\Controllers\SuperAdmin\Communicate\CommunicateController::class, 'sendEmail'])->name('superadmin.communicate.send-email');
        Route::post('/send-notice', [\App\Http\Controllers\SuperAdmin\Communicate\CommunicateController::class, 'sendNotice'])->name('superadmin.communicate.send-notice');
    });

    // ============================================
    // Backup Management
    // ============================================
    Route::prefix('backups')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\Backup\BackupController::class, 'index'])->name('superadmin.backup.index');
        Route::post('/create', [\App\Http\Controllers\SuperAdmin\Backup\BackupController::class, 'create'])->name('superadmin.backup.create');
        Route::get('/download/{filename}', [\App\Http\Controllers\SuperAdmin\Backup\BackupController::class, 'download'])->name('superadmin.backup.download');
        Route::delete('/{filename}', [\App\Http\Controllers\SuperAdmin\Backup\BackupController::class, 'destroy'])->name('superadmin.backup.destroy');
    });

    // ============================================
    // School Impersonation
    // ============================================
    Route::prefix('impersonate')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\Impersonate\ImpersonateController::class, 'index'])->name('superadmin.impersonate.index');
        Route::post('/{schoolId}', [\App\Http\Controllers\SuperAdmin\Impersonate\ImpersonateController::class, 'impersonate'])->name('superadmin.impersonate.start')->where('schoolId', '[0-9]+');
        Route::get('/stop', [\App\Http\Controllers\SuperAdmin\Impersonate\ImpersonateController::class, 'stopImpersonating'])->name('superadmin.impersonate.stop');
    });

    // ============================================
    // System Logs
    // ============================================
    Route::prefix('system-logs')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\SystemLog\SystemLogController::class, 'index'])->name('superadmin.system-logs.index');
        Route::post('/clear', [\App\Http\Controllers\SuperAdmin\SystemLog\SystemLogController::class, 'clear'])->name('superadmin.system-logs.clear');
        Route::get('/download/{filename}', [\App\Http\Controllers\SuperAdmin\SystemLog\SystemLogController::class, 'download'])->name('superadmin.system-logs.download');
    });

    // ============================================
    // Helpdesk / Tickets
    // ============================================
    Route::prefix('tickets')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\Tickets\TicketController::class, 'index'])->name('superadmin.tickets.index');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\Tickets\TicketController::class, 'show'])->name('superadmin.tickets.show')->where('id', '[0-9]+');
        Route::post('/{id}/reply', [\App\Http\Controllers\SuperAdmin\Tickets\TicketController::class, 'reply'])->name('superadmin.tickets.reply')->where('id', '[0-9]+');
        Route::post('/{id}/status', [\App\Http\Controllers\SuperAdmin\Tickets\TicketController::class, 'updateStatus'])->name('superadmin.tickets.status')->where('id', '[0-9]+');
    });

});