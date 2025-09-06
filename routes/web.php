<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/dashboard', [PostController::class, 'index']);