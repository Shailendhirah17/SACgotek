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



Route::group(['middleware' => ['subdomain']], function () {
    Route::prefix('jitsi')->group(function () {
        Route::get('/', 'JitsiController@index');
    });


    Route::prefix('jitsi')->group(function () {
        Route::name('jitsi.')->middleware('auth')->group(function () {

            Route::get('virtual-class', 'JitsiVirtualClassController@index')->name('virtual-class');
            Route::get('virtual-class/child/{id}', 'JitsiVirtualClassController@myChild')->name('parent.virtual-class')->middleware('userRolePermission:109');
            Route::post('virtual-class/store', 'JitsiVirtualClassController@store')->name('virtual-class.store')->middleware('userRolePermission:818');
            Route::delete('virtual_class/{id}', 'JitsiVirtualClassController@destroy')->name('virtual_class.destroy')->middleware('userRolePermission:820');
            Route::get('virtual-class-show/{id}', 'JitsiVirtualClassController@show')->name('virtual-class.show');
            Route::get('virtual-class-edit/{id}', 'JitsiVirtualClassController@edit')->name('virtual-class.edit');
            Route::post('virtual-class/{id}', 'JitsiVirtualClassController@update')->name('virtual-class.update')->middleware('userRolePermission:819');


            Route::get('meetings', 'JitsiMeetingController@index')->name('meetings');
            Route::get('meetings/parent', 'JitsiMeetingController@index')->name('parent.meetings')->middleware('userRolePermission:110');
            Route::post('meetings', 'JitsiMeetingController@store')->name('meetings.store')->middleware('userRolePermission:823');
            Route::get('meetings-show/{id}', 'JitsiMeetingController@show')->name('meetings.show');
            Route::get('meetings-edit/{id}', 'JitsiMeetingController@edit')->name('meetings.edit');
            Route::post('meetings/{id}', 'JitsiMeetingController@update')->name('meetings.update')->middleware('userRolePermission:824');
            Route::delete('meetings/{id}', 'JitsiMeetingController@destroy')->name('meetings.destroy')->middleware('userRolePermission:825');

            Route::get('user-list-user-type-wise', 'JitsiMeetingController@userWiseUserList')->name('user.list.user.type.wise');
            Route::get('virtual-class-room/{id}', 'JitsiMeetingController@meetingStart')->name('meeting.join');


            Route::get('meeting-start/{id}', 'JitsiMeetingController@meetingStart')->name('meeting.start');
            Route::get('meeting-join/{id}', 'JitsiMeetingController@meetingJoin')->name('meeting.join');


            Route::get('class-start/{id}', 'JitsiVirtualClassController@classStart')->name('class.start');
            Route::get('class-join/{id}', 'JitsiVirtualClassController@classJoin')->name('class.join');

            Route::get('settings', 'JitsiSettingController@settings')->name('settings')->middleware('userRolePermission:831');
            Route::post('settings', 'JitsiSettingController@updateSettings')->name('settings.update')->middleware('userRolePermission:832');


            Route::get('virtual-class-reports.', 'JitsiReportController@index')->name('virtual.class.reports.show')->middleware('userRolePermission:827');
            Route::get('meeting-reports', 'JitsiReportController@meetingReport')->name('meeting.reports.show')->middleware('userRolePermission:829');


        });
    });
});

