<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/status', function () {
        return response()->json([
            'status' => 'operational',
            'version' => 'v1',
            'timestamp' => now()->toIso8601String(),
        ]);
    });
});
