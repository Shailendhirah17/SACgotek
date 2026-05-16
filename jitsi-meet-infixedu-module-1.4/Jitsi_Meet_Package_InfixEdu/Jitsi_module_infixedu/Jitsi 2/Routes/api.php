<?php

use Modules\Jitsi\Http\Controllers\Api\JitsiApiController;

Route::middleware(['auth:api', 'subdomain'])->group(function () {

    Route::get('jitsi/virtual-class/{record_id}', [JitsiApiController::class, 'index']);
    Route::get('jitsi/meetings', [JitsiApiController::class, 'meetings']);

});