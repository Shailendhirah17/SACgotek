<?php

Route::group(['middleware' => ['auth', 'subdomain']], function () {
    Route::prefix('smarttransport')->group(function() {
        Route::get('/', 'SmartTransportController@index')->name('stp.index');
        Route::get('/live-tracking', 'SmartTransportController@liveTracking')->name('stp.live_tracking');
        Route::get('/driver-metrics', 'SmartTransportController@driverMetrics')->name('stp.driver_metrics');
        Route::get('/parent-view', 'SmartTransportController@parentView')->name('stp.parent_view');
    });
});
