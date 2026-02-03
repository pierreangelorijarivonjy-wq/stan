<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Health Check Route
|--------------------------------------------------------------------------
|
| This route is used by Docker health checks and monitoring systems
| to verify that the application is running correctly.
|
*/

Route::get('/health', function () {
    $checks = [
        'app' => 'ok',
        'database' => 'error',
        'redis' => 'error',
    ];

    try {
        // Check database connection
        DB::connection()->getPdo();
        $checks['database'] = 'ok';
    } catch (\Exception $e) {
        $checks['database'] = 'error: ' . $e->getMessage();
    }

    try {
        // Check Redis connection
        Redis::ping();
        $checks['redis'] = 'ok';
    } catch (\Exception $e) {
        $checks['redis'] = 'error: ' . $e->getMessage();
    }

    $allHealthy = $checks['database'] === 'ok' && $checks['redis'] === 'ok';

    return response()->json([
        'status' => $allHealthy ? 'healthy' : 'unhealthy',
        'timestamp' => now()->toIso8601String(),
        'checks' => $checks,
        'version' => config('app.version', '1.0.0'),
    ], $allHealthy ? 200 : 503);
})->name('health');
