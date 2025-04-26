<?php

use Illuminate\Support\Facades\Route;
use Modules\Organization\app\Livewire\Tenant\Admin\Index;

Route::middleware(['auth', 'verified'])->group(function () {
    // Tenant admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('organizations', Index::class)->name('organization.index');
    });
});
