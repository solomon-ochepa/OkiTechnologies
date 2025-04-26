<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\App\Http\Controllers\WalletController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('wallet', [WalletController::class, 'index'])->name('wallet');
});
