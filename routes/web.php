<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LibraryAdminController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Middleware\Authenticate;
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
        Route::post('/profile-update', 'profileUpdate')->name('profile-update');
        Route::post('/password-update', 'passwordUpdate')->name('password-update');
    });
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');
    Route::post('/check-user', 'initiateResetFormData')->name('check.user');

    Route::post('/verify-otp',  'verifyOtp')->name('verify.otp');
    Route::post('/reset-password',  'resetPassword')->name('reset.password');
});

Route::post('/test-reset', function () {
    return 'working';
});

Route::controller(MemberController::class)->group(function () {

    Route::middleware(Authenticate::class)->group(function () {
        Route::get('/member-dashboard', 'showDashboard')->name('member.dashboard');
        // Route::get('books', 'books')->name('books');

        Route::get('/browse-books', 'books')->name('browse.books');
        Route::get('/borrowing-history',  'books')->name('borrowing.history');
        Route::get('/reserved-books', 'books')->name('reserved.books');
        Route::get('/books',  'books')->name('books'); // e-Books
        Route::get('/settings',  'books')->name('settings'); // e-Books
    });
});

    //library admin sidebar features
Route::controller(LibraryAdminController::class)->group(function(){
    Route::middleware([Authenticate::class,RoleMiddleware::class.':library_admin'])->group(function (){
        Route::get('/dashboard','showDashboard')->name('libraryadmin.dashboard');
        Route::get('/manage-books','showLibrarybooks')->name('manage.books');
    });
});

Route::controller(BookController::class)->group(function(){
    Route::middleware([Authenticate::class,RoleMiddleware::class.':library_admin'])->group(function (){
        // Route::get('/manage-books','showDashbooks')->name('libraryadmin.dashboard');
    });
});


        //super admin controller (sidebar features)
Route::controller(SuperAdminController::class)->group(function () {
    Route::middleware([Authenticate::class, RoleMiddleware::class.':super_admin'])->group(function () {
        Route::get('/superadmin-dashboard', 'showDashboard')->name('superadmin.dashboard');
        Route::get('/manage-library/{library}/assign-admin', 'assignAdminForm')->name('assign.admin');
        Route::post('/manage-library/{library}/assign-admin', 'assignAdminToLibrary')->name('assign.admin');
        Route::get('/members', 'showAllMembers')->name('all.members');
    });
});

        //library controller functioned by super admin
Route::controller(LibraryController::class)->group(function () {

    Route::middleware(Authenticate::class , RoleMiddleware::class.':super_admin')->group(function () {
        Route::get('/add-library',  'createLibraryForm')->name('add.library');
        Route::post('/store-library',  'createLibrary')->name('store.library');

        Route::get('/manage-library',  'showLibraries')->name('manage.library');
        Route::get('/restricted-libraries', 'restricted')->name('restricted.libraries');

        Route::get('/library-admins', 'showLibraryAdmins')->name('library.admins');
        Route::get('/library-admin-edit/{admin_id}', 'editLibraryAdminDetails')->name('library.admin.edit');

    });
});

