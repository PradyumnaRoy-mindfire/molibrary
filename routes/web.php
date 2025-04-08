<?php

use App\Http\Controllers\AuthController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('welcome');
});


Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegisterForm')->name('register.form');
    Route::post('/register', 'registerUser')->name('register');

    Route::get('/login', 'showLoginForm')->name('login.form');
    Route::post('/login', 'loginUser')->name('login');

    Route::get('/forgot-password','forgotPassword')->name('forgot-password');
    
    Route::middleware(Authenticate::class)->group(function () {
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/logout','logout')->name('logout');
        Route::get('/dashboard','dashboard')->name('dashboard');
        Route::post('/profile-update','profileUpdate')->name('profile-update');
        Route::post('/password-update','passwordUpdate')->name('password-update');
    });
});
