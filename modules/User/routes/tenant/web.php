<?php

use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\Admin\UserController;
use Modules\User\app\Livewire\Admin\Index;
use Modules\User\app\Livewire\Settings\Appearance;
use Modules\User\app\Livewire\Settings\Password;
use Modules\User\app\Livewire\Settings\Profile;

// User profile
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// /admin
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // /admin/users
    Route::resource('users', UserController::class)->except(['index'])->names('user');
    Route::get('users', Index::class)->name('user.index');
    Route::patch('users', [UserController::class, 'update'])->name('user.update');
});
