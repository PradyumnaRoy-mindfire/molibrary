<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardControler;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Librarian\LibrarianController;
use App\Http\Controllers\LibraryAdmin\BookController;
use App\Http\Controllers\LibraryAdmin\LibraryAdminController;
use App\Http\Controllers\LibraryAdmin\ManageGenreController;
use App\Http\Controllers\Member\BorrowController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Member\PaymentController;
use App\Http\Controllers\SuperAdmin\LibraryController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Middleware\CheckMembership;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login.form');
});
Route::get('/', function () {
    return view('welcome');
});


//auth controller(register,login,logout,profile)
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

//forgot password
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');
    Route::post('/check-user', 'initiateResetFormData')->name('check.user');

    Route::post('/verify-otp',  'verifyOtp')->name('verify.otp');
    Route::post('/reset-password',  'resetPassword')->name('reset.password');
});

//show role wise dahsboard after login
Route::controller(DashboardControler::class)->group(function () {

    Route::middleware(Authenticate::class)->group(function () {
        Route::get('/dashboard', 'showDashboard')->name('dashboard');

            //member
        Route::get('/membership/details', 'showMembershipDetails')->name('membership.details');
        Route::get('/membership/features', 'showMembershipFeatures')->name('membership.features');
        Route::get('/active/library', 'activeLibrary')->name('active.library');
        
            //super admin
        Route::get('/top-books', 'topChoicesBooks')->name('top.books');
        Route::get('/total-revenue', 'totalRevenue')->name('total.revenue');
        Route::get('/popular-libraries', 'popularLibraries')->name('popular.libraries');
        
            //library admin
        
    });
});


//member all features 
Route::controller(MemberController::class)->group(function () {

    Route::middleware(Authenticate::class, RoleMiddleware::class . ':member')->group(function () {
        Route::get('/browse-books', 'browseBooks')->name('browse.books');
        Route::get('/borrowing-history',  'showBorrowHistory')->name('borrowing.history');
        Route::get('/reserved-books', 'books')->name('reserved.books');
        Route::get('/books',  'books')->name('books'); // e-Books
        Route::get('/settings',  'books')->name('settings');
        Route::get('/memberships',  'showMembershipAndPlans')->name('memberships');
        //search in books dynamically
        Route::post('/books/search', 'bookSearch')->name('books.search');
    });
});

//borrowcontroller handled by member
Route::controller(BorrowController::class)->group(function () {

    Route::middleware(Authenticate::class, RoleMiddleware::class . ':member', CheckMembership::class)->group(function () {
        Route::get('/browse-books/borrow-confirmation/{book}', 'borrowConfirmation')->name('borrow.confirmation');
        Route::get('/borrow-books/{book}', 'borrowBooks')->name('borrow.books');
    });
});


//payment controller for member 
Route::controller(PaymentController::class)->group(function () {

    Route::middleware(Authenticate::class, RoleMiddleware::class . ':member')->group(function () {
           Route::get('/checkout/{plan}', 'showCheckoutForm')->name('checkout');
           Route::post('/checkout/{plan}', 'processCheckout')->name('checkout.process');

        // In routes/web.php

       
    });
});

    //librarian
Route::controller(LibrarianController::class)->group(function () {

    Route::middleware([Authenticate::class, RoleMiddleware::class . ':librarian'])->group(function () {
        Route::get('/books', 'showBorrowHistory')->name('librarian.borrowing.history');
        Route::get('/book-management', 'bookManagement')->name('book.management');

        Route::post('/library/process-request/{id}', 'processRequest')->name('library.process-request');
    });
});

//library admin sidebar features
Route::controller(LibraryAdminController::class)->group(function () {

    Route::middleware([Authenticate::class, RoleMiddleware::class . ':library_admin'])->group(function () {
        Route::get('/manage-books', 'showLibrarybooks')->name('manage.books');
        Route::get('/approve-librarians', 'approveLibrarians')->name('approve.librarians');

        // Route for handling the Accept and Reject actions
        Route::post('/librarians/{id}/status/{action}', 'updateStatus')->name('librarians.accept_or_reject')->where('action', 'accept|reject');

    });
});

//book mamanagement by library admin
Route::controller(BookController::class)->group(function () {

    Route::middleware([Authenticate::class, RoleMiddleware::class . ':library_admin'])->group(function () {
        Route::get('/manage-books/{library}/add-book', 'addBookForm')->name('add.book');
        Route::post('/manage-books/{library}/store-book', 'storeBook')->name('book.store');

        Route::get('/manage-books/{book}/edit-book', 'editBookForm')->name('edit.book');
        Route::put('/manage-books/{book}/edit-book', 'editBook')->name('edit.book');

        Route::get('/manage-books/{book}/delete-book', 'deleteBook')->name('delete.book');
    });
});

//Manage Booksgenres by library admin
Route::controller(ManageGenreController::class)->group(function () {

    Route::middleware([Authenticate::class, RoleMiddleware::class . ':library_admin'])->group(function () {
        Route::get('/manage-genres', 'showGenres')->name('manage.genres');

        Route::get('/manage-genres/add-genre', 'addGenre')->name('add.genre');
        Route::post('/manage-genres/add-genre', 'storeGenre')->name('store.genre');

        Route::put('/manage-genres/update-genre/{category}', 'updateGenre')->name('update.genre');
    });
});


//super admin controller (sidebar features)
Route::controller(SuperAdminController::class)->group(function () {

    Route::middleware([Authenticate::class, RoleMiddleware::class . ':super_admin'])->group(function () {
        Route::get('/manage-library/{library}/assign-admin', 'assignAdminForm')->name('assign.admin');
        Route::post('/manage-library/{library}/assign-admin', 'assignAdminToLibrary')->name('assign.admin');
        Route::get('/members', 'showAllMembers')->name('all.members');
    });
});

//library controller functioned by super admin
Route::controller(LibraryController::class)->group(function () {

    Route::middleware(Authenticate::class, RoleMiddleware::class . ':super_admin')->group(function () {
        Route::get('/add-library',  'createLibraryForm')->name('add.library');
        Route::post('/store-library',  'createLibrary')->name('store.library');

        Route::get('/manage-library',  'showLibraries')->name('manage.library');
        Route::get('/restricted-libraries', 'restricted')->name('restricted.libraries');

        Route::get('/library-admins', 'showLibraryAdmins')->name('library.admins');
        Route::get('/library-admin-edit/{admin_id}', 'editLibraryAdminDetails')->name('library.admin.edit');
    });
});
