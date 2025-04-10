<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MemberController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('welcome');
});

Route::get('/dashboard', function ()  {
    return view('super_admin.dashboard');
});

Route::get('/send-mail',[MailController::class,'sendMail']);

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegisterForm')->name('register.form');
    Route::post('/register', 'registerUser')->name('register');

    Route::get('/login', 'showLoginForm')->name('login.form');
    Route::post('/login', 'loginUser')->name('login');

  
    
    Route::middleware(Authenticate::class)->group(function () {
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/logout','logout')->name('logout');
        // Route::get('/dashboard','dashboard')->name('dashboard');
        Route::post('/profile-update','profileUpdate')->name('profile-update');
        Route::post('/password-update','passwordUpdate')->name('password-update');
    });
});

Route::controller(ForgotPasswordController::class)->group(function() {
    Route::get('/forgot-password','forgotPassword')->name('forgot-password');
    Route::post('/check-user','initiateResetFormData')->name('check-user');

    Route::post('/verify-otp',  'verifyOtp')->name('verify-otp');
    Route::get('/reset-password',  'resetPassword')->name('verify-otp');

});


Route::controller(MemberController::class)->group(function () {
    
    Route::middleware(Authenticate::class)->group(function () {
        Route::get('dashboard','showDashboard')->name('dashboard');
    });
});

