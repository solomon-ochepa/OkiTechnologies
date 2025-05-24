<?php

use Illuminate\Support\Facades\Route;
use Modules\Organization\App\Http\Controllers\Tenant\Api\OrganizationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('organizations', [OrganizationController::class, 'index'])->name('organization.index');
});
