<?php

use App\Http\Controllers\apiCallController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StrinpController;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JwtAuthController;
use App\Http\Controllers\UploadController;
use App\Jobs\PodcastPublish;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('layouts.app');
});

Route::get('/posts', [PostController::class, 'index']);
// Route::get('/dashboard', [PostController::class, 'index']);

//Payment Gateway
Route::get('/checkout', [StrinpController::class, 'checkout'])->name('checkout');
Route::post('/session', [StrinpController::class, 'session'])->name('session');
Route::get('/success', [StrinpController::class, 'success'])->name('success');
Route::get('/cancel', [StrinpController::class, 'cancel'])->name('cancel');


Route::get('/register', [JwtAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [JwtAuthController::class, 'register'])->name('register.submit');
Route::get('/login', [JwtAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [JwtAuthController::class, 'login'])->name('login.submit');
// Route::post('/token/refresh', [JwtAuthController::class, 'refresh'])->name('token.refresh');

Route::middleware(['jwt.session'])->group(function () {
    Route::get('/dashboard', [JwtAuthController::class, 'dashboard']);
    Route::post('/logout', [JwtAuthController::class, 'logout'])->name('logout');
});

// Route::get('concurrency', function (SampleClass $sampleClass) {

//     [$task1, $task2] = Concurrency::run([
//         fn() => $sampleClass->taskOne(),
//         fn() => $sampleClass->taskTwo(),
//     ]);


//     dump($task1);
//     dump($task2);

//     dd('I am done');
// });

// class SampleClass
// {
//     public function taskOne()
//     {
//         sleep(3);
//         return 3;
//     }

//     public function taskTwo()
//     {
//         sleep(5);
//         return 5;
//     }
// }


Route::get('pokemon', function () {

    [$ditto, $pikachu, $bulbasaur] = Concurrency::run([
        fn() => Http::get('https://rickandmortyapi.com/api/character/1')->json(),
        fn() => Http::get('https://pokeapi.co/api/v2/pokemon/pikachu')->json(),
        fn() => Http::get('https://pokeapi.co/api/v2/pokemon/1')->json(),
    ]);

    dump(array_keys($ditto));       
    dump($pikachu['name']);    // pikachu
    dump($bulbasaur['name']);  // bulbasaur
});

Route::get('/', function() {
    Log::info('Welcome to Laravel');

    PodcastPublish::dispatch();
    return view('welcome');
});

Route::get('/logs', function () {
    Log::info('Hay!, we made it!');
});

Route::get('/upload', [UploadController::class, 'viewUpload'])->name('upload');
Route::post('/upload.submit', [UploadController::class, 'fileUpload'])->name('upload.submit');


Route::get('/pokemon',[apiCallController::class, 'index']);


// for Localization
Route::get('/lan', function () {
    return view('welcome');
});

// for Mail


Route::get('/send-mail', [MailController::class, 'showForm']);
Route::post('/send-mail', [MailController::class, 'sendMail'])->name('send.mail');