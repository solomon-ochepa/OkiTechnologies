<?php

use Illuminate\Support\Facades\Route;
use Modules\Transaction\App\Http\Controllers\TransactionController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('transactions', TransactionController::class)->names('transaction');
});
