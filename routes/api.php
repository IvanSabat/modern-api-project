<?php

use App\Http\Controllers\Api\V2\PostController as V2PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'controller' => '\App\Http\Controllers\Api\V1\PostController'], function () {
    Route::get('/posts', 'index');
    Route::get('/posts/{post}', 'show');
    Route::post('/posts', 'store');
    Route::patch('/posts/{post}', 'update');
    Route::delete('/posts/{post}', 'destroy');
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('v2')->group(function () {
        Route::apiResource('posts', V2PostController::class);
    });
});

require __DIR__.'/auth.php';
