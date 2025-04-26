<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\App\Http\Controllers\WalletController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('wallet', WalletController::class)->names('wallet');
});
