<?php

use Illuminate\Support\Facades\Route;
use Modules\Status\App\Http\Controllers\StatusController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('status', StatusController::class)->names('status');
});
