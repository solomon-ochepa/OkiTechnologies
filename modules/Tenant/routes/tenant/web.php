<?php

use Illuminate\Support\Facades\Route;
use Modules\Tenant\App\Http\Controllers\Tenant\Admin\TenantController as AdminTenantController;
use Modules\Tenant\App\Http\Controllers\Tenant\TenantController;

// Impersonate a tenant user from the central domain
Route::get('/impersonate/{token}', [TenantController::class, 'impersonate'])->name('tenant.impersonate');

Route::get('/', [TenantController::class, 'home'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [TenantController::class, 'dashboard'])->name('dashboard');
});

// 'admin/*'
Route::middleware(['auth', 'permission:admin.dashboard'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', 'admin/dashboard');

    Route::get('dashboard', [AdminTenantController::class, 'dashboard'])->name('dashboard');
});
