<?php

use Illuminate\Support\Facades\Route;
use Modules\Permission\App\Http\Controllers\PermissionController;
use Modules\Permission\app\Livewire\Index;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('permissions', PermissionController::class)->except(['index'])->names('permission');
    Route::get('permissions', Index::class)->name('permission.index');

    // Trash
    Route::get('permissions/{permission}/restore', [PermissionController::class, 'restore'])->name('permission.restore');
    Route::delete('permissions/{permission}/permanent', [PermissionController::class, 'permanent'])->name('permission.destroy.permanent');
});
