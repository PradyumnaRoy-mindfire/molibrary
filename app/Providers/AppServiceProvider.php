<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Borrow;
use App\Observers\BookObserver;
use App\Observers\BorrowObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrap();

        //for notication observer
        Book::observe(BookObserver::class);
        Borrow::observe(BorrowObserver::class);
    }
}
