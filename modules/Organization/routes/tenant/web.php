<?php

use Illuminate\Support\Facades\Route;
use Modules\Organization\App\Http\Controllers\Tenant\OrganizationController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Tenant admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('organizations', [OrganizationController::class, 'index'])->name('organization.index');
    });
});
