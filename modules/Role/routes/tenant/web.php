<?php

use Illuminate\Support\Facades\Route;
use Modules\Role\App\Http\Controllers\RoleController;
use Modules\Role\app\Livewire\Index;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('roles', RoleController::class)->except(['index'])->names('role');
    Route::get('roles', Index::class)->name('role.index');

    // Trash
    Route::get('roles/{role}/restore', [RoleController::class, 'restore'])->name('role.restore');
    Route::delete('roles/{role}/permanent', [RoleController::class, 'permanent'])->name('role.destroy.permanent');
});
