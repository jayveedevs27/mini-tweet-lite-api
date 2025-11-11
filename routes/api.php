<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tweets', TweetController::class)->only(['index','store','show','update','destroy']);
    Route::post('tweets/{tweet}/like', [LikeController::class, 'toggle']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/profile/upload', [ProfileController::class, 'upload']);
    Route::put('/profile/update', [ProfileController::class, 'update']);
});
