<?php

use App\Http\Controllers\PostViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

