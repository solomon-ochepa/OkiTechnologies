<?php

use Illuminate\Support\Facades\Route;
use Modules\Status\App\Http\Controllers\StatusController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('status', StatusController::class)->names('status');
});
