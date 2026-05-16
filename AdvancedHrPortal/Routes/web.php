<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'subdomain']], function () {
    Route::prefix('advancedhrportal')->group(function() {
        // Admin / HR Manager Routes
        Route::get('/', 'AdvancedHrPortalController@index')->name('ahp.index');
        Route::get('/360-staff-view', 'AdvancedHrPortalController@staffView')->name('ahp.staff_view');
        Route::get('/payroll-master', 'AdvancedHrPortalController@payroll')->name('ahp.payroll');
        Route::get('/attendance-monitor', 'AdvancedHrPortalController@attendance')->name('ahp.attendance');

        // Staff Self-Service Routes
        Route::get('/my-hr-hub', 'AdvancedHrPortalController@staffDashboard')->name('ahp.staff_dashboard');
    });
});
