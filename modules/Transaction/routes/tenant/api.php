<?php

use Illuminate\Support\Facades\Route;
use Modules\Transaction\App\Http\Controllers\TransactionController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('transaction', TransactionController::class)->names('transaction');
});
