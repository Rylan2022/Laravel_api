<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\StrinpController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/dashboard', [PostController::class, 'index']);

//Payment Gateway
Route::get('/checkout', [StrinpController::class, 'checkout'])->name('checkout');
Route::post('/session', [StrinpController::class, 'session'])->name('session');
Route::get('/success', [StrinpController::class, 'success'])->name('success');
Route::get('/cancel', [StrinpController::class, 'cancel'])->name('cancel');
