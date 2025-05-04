<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Output\BufferedOutput;

Route::prefix('cli')->name('cli.')->group(function () {
    // Migrate base and all modules migrations
    Route::get('/migrate', function (Request $request) {
        Config::set('app.env', 'local');
        Config::set('app.debug', true);

        $output = new BufferedOutput;
        $queries = $request->query();

        Artisan::call('migrate --force', $queries, $output);

        // Display the output
        return nl2br(e($output->fetch()));
    })->name('migrate');

    Route::get('/seed', function (Request $request) {
        Config::set('app.env', 'local');
        Config::set('app.debug', true);

        $output = new BufferedOutput;
        $queries = $request->query();

        Artisan::call('db:seed --force', $queries, $output);
        Artisan::call('module:seed --all --force', $queries, $output);

        // Display the output
        return nl2br(e($output->fetch()));
    })->name('seed');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/', function (Request $request) {
            Config::set('app.env', 'local');
            Config::set('app.debug', true);

            $output = new BufferedOutput;

            Artisan::call(($request->run ?? 'optimize:clear'), ['--force' => true], $output);

            // Display the output
            return nl2br(e($output->fetch()));
        })->name('index');
    });
});
