<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\App\Http\Controllers\WalletController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('wallet', WalletController::class)->names('wallet');
});
