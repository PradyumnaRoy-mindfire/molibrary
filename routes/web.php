<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LibraryAdminController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('welcome');
});


Route::get('/send-mail', [MailController::class, 'sendMail']);

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegisterForm')->name('register.form');
    Route::post('/register', 'registerUser')->name('register');

    Route::get('/login', 'showLoginForm')->name('login.form');
    Route::post('/login', 'loginUser')->name('login');



    Route::middleware(Authenticate::class)->group(function () {
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/logout', 'logout')->name('logout');
        // Route::get('/dashboard','dashboard')->name('dashboard');
        Route::post('/profile-update', 'profileUpdate')->name('profile-update');
        Route::post('/password-update', 'passwordUpdate')->name('password-update');
    });
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');
    Route::post('/check-user', 'initiateResetFormData')->name('check-user');

    Route::post('/verify-otp',  'verifyOtp')->name('verify-otp');
    Route::get('/reset-password',  'resetPassword')->name('verify-otp');
});


Route::controller(MemberController::class)->group(function () {

    Route::middleware(Authenticate::class)->group(function () {
        Route::get('/member-dashboard', 'showDashboard')->name('member-dashboard');
        // Route::get('books', 'books')->name('books');
        // routes/web.php

        Route::get('/browse-books', 'books')->name('browse-books');
        Route::get('/borrowing-history',  'books')->name('borrowing-history');
        Route::get('/reserved-books', 'books')->name('reserved-books');
        Route::get('/books',  'books')->name('books'); // e-Books
        Route::get('/settings',  'books')->name('settings'); // e-Books
        // to show all the member to the super admin
        Route::get('/members', 'index')->name('all.members');
    });
});


Route::controller(LibraryController::class)->group(function () {

    Route::middleware(Authenticate::class)->group(function () {
        Route::get('/add-library',  'createLibraryForm')->name('add.library');
        Route::post('/store-library',  'createLibrary')->name('store-library');

        Route::get('/manage-library',  'index')->name('manage.library');
        Route::get('/restricted-libraries', 'restricted')->name('restricted.libraries');
    });
});

Route::controller(LibraryAdminController::class)->group(function(){
    Route::middleware(Authenticate::class)->group(function (){
        Route::get('/library-admins', 'index')->name('library.admins');
    });
});

Route::controller(SuperAdminController::class)->group(function(){
    Route::middleware(['Authenticate','role:super_admin'])->group(function (){
        Route::get('/superadmin-dashboard', 'showDashboard')->name('superadmin-dashboard');
    });
});
