<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('v1')->group(function () {});
