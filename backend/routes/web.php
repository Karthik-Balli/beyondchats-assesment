<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return response()->json([
        'app' => 'BeyondInbox API',
        'status' => 'running'
    ]);
});

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);

Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);