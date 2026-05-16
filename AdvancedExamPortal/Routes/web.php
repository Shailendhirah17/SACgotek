<?php

Route::group(['middleware' => ['auth', 'subdomain']], function () {
    Route::prefix('advancedexamportal')->group(function() {
        Route::get('/', 'AdvancedExamPortalController@index')->name('aep.index');
        Route::get('/ai-question-generator', 'AdvancedExamPortalController@aiGenerator')->name('aep.ai_generator');
        Route::get('/result-heatmap', 'AdvancedExamPortalController@resultHeatmap')->name('aep.result_heatmap');
        Route::get('/portfolio-assessment', 'AdvancedExamPortalController@portfolio')->name('aep.portfolio');
    });
});
