<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'subdomain']], function () {
    Route::prefix('automatedtimetable')->group(function() {
        Route::get('/', 'AutomatedTimetableController@index')->name('automatedtimetable.index');
        Route::get('/editor', 'AutomatedTimetableController@editor')->name('automatedtimetable.editor');
        Route::post('/editor', 'AutomatedTimetableController@fetchRoutine')->name('automatedtimetable.fetch');
        
        Route::post('/generate', 'AutomatedTimetableController@generate')->name('automatedtimetable.generate');
        Route::post('/swap', 'AutomatedTimetableController@swap')->name('automatedtimetable.swap');
        Route::post('/assign', 'AutomatedTimetableController@assign')->name('automatedtimetable.assign');
        Route::post('/free', 'AutomatedTimetableController@free')->name('automatedtimetable.free');

        // Rules Routes
        Route::get('/rules', 'AutomatedTimetableController@rules')->name('automatedtimetable.rules');
        Route::post('/rules', 'AutomatedTimetableController@saveRules')->name('automatedtimetable.save_rules');

        Route::get('/substitutes', 'AutomatedTimetableController@substitutes')->name('automatedtimetable.substitutes');
        Route::post('/substitutes/assign', 'AutomatedTimetableController@assignSubstitute')->name('automatedtimetable.assign_substitute');
    });
});
