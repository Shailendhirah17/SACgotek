<?php

Route::group(['middleware' => ['auth', 'subdomain']], function () {
    Route::prefix('smartfrontoffice')->group(function() {
        Route::get('/', 'SmartFrontOfficeController@index')->name('sfo.index');
        Route::get('/admission-pipeline', 'SmartFrontOfficeController@admissionPipeline')->name('sfo.admission_pipeline');
        Route::get('/visitor-passes', 'SmartFrontOfficeController@visitorPasses')->name('sfo.visitor_passes');
        Route::get('/leads', 'SmartFrontOfficeController@leads')->name('sfo.leads');
    });
});
