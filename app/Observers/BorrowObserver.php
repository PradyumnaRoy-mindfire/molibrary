<?php

namespace App\Observers;

use App\Events\NewBorrowRequest;
use App\Events\NewReturnRequest;
use App\Models\Borrow;

class BorrowObserver
{
    public function created(Borrow $borrow)
    {
        // Trigger borrow event
        $book = $borrow->book;
        if ($borrow->type == 'borrow') {
            event(new NewBorrowRequest([
                'book' => $book->title
            ]));
        }
    }

    public function updated(Borrow $borrow) {
        $book = $borrow->book;
        if($borrow->type == 'return') {
            event(new NewReturnRequest([
                'book' => $book->title
            ]));
        }
    }
}
