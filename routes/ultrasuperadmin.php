<?php

/*
|--------------------------------------------------------------------------
| Ultra Super Admin Routes
|--------------------------------------------------------------------------
|
| These routes handle all Ultra Super Admin panel functionality.
| The Ultra Super Admin is the Master Control Layer owned by
| Technosprint Info Solutions with ultimate authority over all
| organizations, school groups, subscriptions, and features.
|
| Hierarchy: Ultra Super Admin → Super Admin → Admin
|
| All protected routes use the 'ultrasuperadmin' middleware which checks
| Auth::guard('ultrasuperadmin') for isolated authentication.
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UltraSuperAdminLoginController;
use App\Http\Controllers\UltraSuperAdmin\DashboardController;
use App\Http\Controllers\UltraSuperAdmin\SchoolGroups\SchoolGroupController;
use App\Http\Controllers\UltraSuperAdmin\SuperAdminManagement\SuperAdminController;
use App\Http\Controllers\UltraSuperAdmin\Subscriptions\SubscriptionController;
use App\Http\Controllers\UltraSuperAdmin\Features\FeatureController;
use App\Http\Controllers\UltraSuperAdmin\Analytics\AnalyticsController;
use App\Http\Controllers\UltraSuperAdmin\Settings\PlatformSettingsController;
use App\Http\Controllers\UltraSuperAdmin\Communication\PlatformBroadcastController;

/*
|--------------------------------------------------------------------------
| Public Ultra Super Admin Routes (Login/Logout)
|--------------------------------------------------------------------------
*/

Route::get('/ultrasuperadmin/login', [UltraSuperAdminLoginController::class, 'showLoginForm'])->name('ultrasuperadmin.login');
Route::post('/ultrasuperadmin/login', [UltraSuperAdminLoginController::class, 'login'])->name('ultrasuperadmin.login.submit');
Route::post('/ultrasuperadmin/logout', [UltraSuperAdminLoginController::class, 'logout'])->name('ultrasuperadmin.logout');
Route::get('/ultrasuperadmin/create-default', [UltraSuperAdminLoginController::class, 'createDefaultUltraSuperAdmin'])->name('ultrasuperadmin.create-default');

/*
|--------------------------------------------------------------------------
| Protected Ultra Super Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('ultrasuperadmin')->middleware(['ultrasuperadmin'])->group(function () {

    // ============================================
    // Dashboard
    // ============================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('ultrasuperadmin-dashboard');

    // ============================================
    // School Group Management
    // ============================================
    Route::prefix('school-groups')->group(function () {
        Route::get('/', [SchoolGroupController::class, 'index'])->name('ultrasuperadmin.school-groups.index');
        Route::get('/create', [SchoolGroupController::class, 'create'])->name('ultrasuperadmin.school-groups.create');
        Route::post('/', [SchoolGroupController::class, 'store'])->name('ultrasuperadmin.school-groups.store');
        Route::get('/{id}', [SchoolGroupController::class, 'show'])->name('ultrasuperadmin.school-groups.show')->where('id', '[0-9]+');
        Route::get('/{id}/edit', [SchoolGroupController::class, 'edit'])->name('ultrasuperadmin.school-groups.edit')->where('id', '[0-9]+');
        Route::put('/{id}', [SchoolGroupController::class, 'update'])->name('ultrasuperadmin.school-groups.update')->where('id', '[0-9]+');
        Route::delete('/{id}', [SchoolGroupController::class, 'destroy'])->name('ultrasuperadmin.school-groups.destroy')->where('id', '[0-9]+');
        Route::post('/toggle-status', [SchoolGroupController::class, 'toggleStatus'])->name('ultrasuperadmin.school-groups.toggle-status');
        Route::post('/{id}/assign-school', [SchoolGroupController::class, 'assignSchool'])->name('ultrasuperadmin.school-groups.assign-school')->where('id', '[0-9]+');
        Route::post('/{id}/remove-school', [SchoolGroupController::class, 'removeSchool'])->name('ultrasuperadmin.school-groups.remove-school')->where('id', '[0-9]+');
    });

    // ============================================
    // Super Admin Management
    // ============================================
    Route::prefix('super-admins')->group(function () {
        Route::get('/', [SuperAdminController::class, 'index'])->name('ultrasuperadmin.super-admins.index');
        Route::get('/create', [SuperAdminController::class, 'create'])->name('ultrasuperadmin.super-admins.create');
        Route::post('/', [SuperAdminController::class, 'store'])->name('ultrasuperadmin.super-admins.store');
        Route::get('/{id}/edit', [SuperAdminController::class, 'edit'])->name('ultrasuperadmin.super-admins.edit')->where('id', '[0-9]+');
        Route::put('/{id}', [SuperAdminController::class, 'update'])->name('ultrasuperadmin.super-admins.update')->where('id', '[0-9]+');
        Route::delete('/{id}', [SuperAdminController::class, 'destroy'])->name('ultrasuperadmin.super-admins.destroy')->where('id', '[0-9]+');
        Route::post('/{id}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('ultrasuperadmin.super-admins.toggle-status')->where('id', '[0-9]+');
    });

    // ============================================
    // Subscription Management
    // ============================================
    Route::prefix('subscriptions')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('ultrasuperadmin.subscriptions.index');
        Route::put('/{id}', [SubscriptionController::class, 'update'])->name('ultrasuperadmin.subscriptions.update')->where('id', '[0-9]+');
        Route::post('/{id}/toggle', [SubscriptionController::class, 'toggleStatus'])->name('ultrasuperadmin.subscriptions.toggle')->where('id', '[0-9]+');
    });

    // ============================================
    // Feature Management
    // ============================================
    Route::prefix('features')->group(function () {
        Route::get('/', [FeatureController::class, 'index'])->name('ultrasuperadmin.features.index');
        Route::post('/toggle', [FeatureController::class, 'toggle'])->name('ultrasuperadmin.features.toggle');
        Route::post('/enable-all', [FeatureController::class, 'enableAll'])->name('ultrasuperadmin.features.enable-all');
        Route::post('/disable-all', [FeatureController::class, 'disableAll'])->name('ultrasuperadmin.features.disable-all');
    });

    // ============================================
    // Analytics
    // ============================================
    Route::prefix('analytics')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('ultrasuperadmin.analytics.index');
    });

    // ============================================
    // Platform Settings
    // ============================================
    Route::prefix('settings')->group(function () {
        Route::get('/', [PlatformSettingsController::class, 'index'])->name('ultrasuperadmin.settings.index');
        Route::post('/clear-cache', [PlatformSettingsController::class, 'clearCache'])->name('ultrasuperadmin.settings.clear-cache');
        Route::post('/toggle-maintenance', [PlatformSettingsController::class, 'toggleMaintenance'])->name('ultrasuperadmin.settings.toggle-maintenance');
    });

    // ============================================
    // Communication Bridge
    // ============================================
    Route::prefix('communication')->group(function () {
        Route::get('/broadcast', [PlatformBroadcastController::class, 'index'])->name('ultrasuperadmin.communication.broadcast');
        Route::post('/broadcast', [PlatformBroadcastController::class, 'store'])->name('ultrasuperadmin.communication.broadcast.store');
        Route::delete('/broadcast/{id}', [PlatformBroadcastController::class, 'destroy'])->name('ultrasuperadmin.communication.broadcast.destroy')->where('id', '[0-9]+');
    });

});
