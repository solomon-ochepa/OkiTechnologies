<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\App\Http\Controllers\WalletController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('wallet', [WalletController::class, 'index'])->name('wallet');
});
