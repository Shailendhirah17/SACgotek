<?php

Route::group(['middleware' => ['auth', 'subdomain']], function () {
    Route::prefix('smartcommunication')->group(function() {
        Route::get('/', 'SmartCommunicationController@index')->name('scom.index');
        Route::get('/tickets', 'SmartCommunicationController@tickets')->name('scom.tickets');
        Route::get('/event-rsvp', 'SmartCommunicationController@eventRsvp')->name('scom.event_rsvp');
        Route::get('/whatsapp-hub', 'SmartCommunicationController@whatsappHub')->name('scom.whatsapp_hub');
    });
});
