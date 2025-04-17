<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardControler;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LibraryAdminController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Middleware\Membership;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login.form');
});


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


        //show role wise dahsboard
Route::controller(DashboardControler::class)->group(function(){
    Route::middleware(Authenticate::class)->group(function() {
        Route::get('/dashboard','showDashboard')->name('dashboard');
    });
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');
    Route::post('/check-user', 'initiateResetFormData')->name('check.user');

    Route::post('/verify-otp',  'verifyOtp')->name('verify.otp');
    Route::post('/reset-password',  'resetPassword')->name('reset.password');
});

        //member all features 
Route::controller(MemberController::class)->group(function () {
    Route::middleware(Authenticate::class,RoleMiddleware::class.':member')->group(function () {
        Route::get('/browse-books', 'browseBooks')->name('browse.books');
        Route::get('/borrowing-history',  'showBorrowHistory')->name('borrowing.history');
        Route::get('/reserved-books', 'books')->name('reserved.books');
        Route::get('/books',  'books')->name('books'); // e-Books
        Route::get('/settings',  'books')->name('settings'); 
        Route::get('/memberships',  'showMembershipAndPlans')->name('memberships'); 
            //search in books dynamically
        Route::post('/books/search','bookSearch')->name('books.search');

        Route::middleware(Membership::class)->group(function () {
            Route::get('/borrow-books', 'bookSearch')->name('borrow.books');
            // Other routes that need membership checks...
        });
    });
});

    //library admin sidebar features
Route::controller(LibraryAdminController::class)->group(function(){
    Route::middleware([Authenticate::class,RoleMiddleware::class.':library_admin'])->group(function (){
        // Route::get('/dashboard','showDashboard')->name('libraryadmin.dashboard');
        Route::get('/manage-books','showLibrarybooks')->name('manage.books');
        
        Route::get('/approve-librarians','approveLibrarians')->name('approve.librarians');
        // Route for handling the Accept and Reject actions
        // Route::get('librarians/{id}/{action}', 'acceptOrReject')->name('librarians.accept_or_reject');

    });
});

Route::controller(BookController::class)->group(function(){
    Route::middleware([Authenticate::class,RoleMiddleware::class.':library_admin'])->group(function (){
        Route::get('/manage-books/{library}/add-book','addBookForm')->name('add.book');
        Route::post('/manage-books/{library}/store-book','storeBook')->name('book.store');

        Route::get('/manage-books/{book}/edit-book','editBookForm')->name('edit.book');
        Route::put('/manage-books/{book}/edit-book','editBook')->name('edit.book');
    });
});


        //super admin controller (sidebar features)
Route::controller(SuperAdminController::class)->group(function () {
    Route::middleware([Authenticate::class, RoleMiddleware::class.':super_admin'])->group(function () {
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

