<?php

use App\Http\Controllers\WeAiChatController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'chat')->name('chat');
Route::post('/chat', WeAiChatController::class)
    ->middleware('throttle:10,1')
    ->name('chat.send');
