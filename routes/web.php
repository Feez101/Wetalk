<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::redirect('/dashboard.html', '/dashboard');
Route::post('/weai/chat', [\App\Http\Controllers\WeaiChatController::class, 'store'])->middleware('throttle:10,1')->name('weai.chat');
