<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\EmailController;

Route::prefix('v1')->group(function () {

    Route::get('/threads', [ThreadController::class, 'index']);

    Route::get('/threads/{id}', [ThreadController::class, 'show']);

    Route::post('/emails/sync', [EmailController::class, 'sync']);

});