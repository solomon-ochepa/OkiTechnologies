<?php

use App\Http\Controllers\Admin\AppController as AdminAppController;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;

// Route::get('/', [AppController::class, 'home'])->name('home');

// // Authenticated users
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('dashboard', [AppController::class, 'dashboard'])->name('dashboard');
// });

// 'admin/*'
Route::middleware(['auth', 'permission:admin.dashboard'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', 'admin/dashboard');

    Route::get('dashboard', [AdminAppController::class, 'dashboard'])->name('dashboard');
});

require_once 'cli.php';
