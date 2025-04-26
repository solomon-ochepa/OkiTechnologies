<?php

use Illuminate\Support\Facades\Route;
use Modules\Organization\App\Http\Controllers\Tenant\OrganizationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('organizations', OrganizationController::class)->names('organization');
});
