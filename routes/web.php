<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::redirect('/dashboard.html', '/dashboard');
Route::redirect('/chat', '/dashboard');
Route::post('/weai/chat', [\App\Http\Controllers\WeAiChatController::class, 'store'])->middleware('throttle:10,1')->name('weai.chat');
Route::post('/chat', \App\Http\Controllers\WeAiChatController::class)->middleware('throttle:10,1')->name('chat.send');
