<?php

use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::middleware('auth')->prefix('v1')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/communities', [CommunityController::class, 'index']);
    Route::post('/communities', [CommunityController::class, 'store']);
    Route::post('/communities/join', [CommunityController::class, 'join']);
    Route::get('/communities/{community}', [CommunityController::class, 'show']);
    Route::post('/communities/{community}/channels', [ChannelController::class, 'store']);
    Route::get('/channels/{channel}/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
});
