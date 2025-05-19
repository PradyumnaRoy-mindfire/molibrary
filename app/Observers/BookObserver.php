<?php

namespace App\Observers;

use App\Events\NewBookAdded;
use App\Models\Book;

class BookObserver
{
    public function created(Book $book)
    {
        // Trigger event
        event(new NewBookAdded([
            'book' => $book->title
        ]));
    }
}
