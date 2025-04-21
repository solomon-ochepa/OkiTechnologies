<?php

use Illuminate\Support\Facades\Route;
use Modules\Tenant\App\Http\Controllers\TenantController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('tenant', TenantController::class)->names('tenant');
});
