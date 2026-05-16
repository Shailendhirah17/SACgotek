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
    Route::prefix('advancedstudentportal')->group(function() {
        Route::get('/', 'AdvancedStudentPortalController@index')->name('asp.index');
        Route::get('/360-student-view', 'AdvancedStudentPortalController@studentView')->name('asp.student_view');
        Route::get('/performance-tracking', 'AdvancedStudentPortalController@performance')->name('asp.performance');
        Route::get('/behavior-badges', 'AdvancedStudentPortalController@behavior')->name('asp.behavior');
        
        // Parent & Student Specific Routes
        Route::get('/parent-dashboard', 'AdvancedStudentPortalController@parentDashboard')->name('asp.parent_dashboard');
        Route::get('/student-self-service', 'AdvancedStudentPortalController@studentDashboard')->name('asp.student_dashboard');
    });
});
