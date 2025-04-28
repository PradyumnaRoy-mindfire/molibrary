<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    //
    public function borrowConfirmation($action, Book $book)
    {
        $library = Library::where('id', $book->library_id)->first();
        $borrowCount = Borrow::where('book_id', $book->id)->count('id');
        return view('member.borrow_confirmation', compact('book', 'library', 'borrowCount', 'action'));
    }

    public function borrowBooks(Book $book)
    {
        $user = Auth::user();
        $book->borrows()->create([
            'library_id' => $book->library_id,
            'users_id' => $user->id,
            'type' => 'borrow',
            'status' => 'pending',
        ]);
        $user->book_limit -= 1;
        $user->save();
        return redirect()->route('borrowing.history')->with('success', 'Borrow Request sent Successfully!!');
    }

    public function reservedBooks(Book $book)
    {
        $user = Auth::user();
        $book->borrows()->create([
            'library_id' => $book->library_id,
            'users_id' => $user->id,
            'borrow_date' => now(),
            'type' => 'reserve',
            'status' => 'active',
        ]);

        return redirect()->route('books')->with('success', 'Book Reserved Successfully!!');
    }

    public function showReservedBooks()
    {
        $user = Auth::user();
        $reservedBooks = $user->borrows()->where('type', 'reserve')->with('book')->get();

        $reservedBooksWithAvailability = $reservedBooks->map(function ($reserve) {
            // Finding the the latest active borrow of that book
            $activeBorrow = Borrow::where('book_id', $reserve->book_id)
                ->where('status', 'borrowed')
                ->orderBy('due_date', 'asc') // nearest due first
                ->first();
            
            if ($activeBorrow) {
                $dueDate = $activeBorrow->due_date;

                if (now()->gt($dueDate)) {  // Due date already passed but not returned
                    $expectedAvailability = now()->addDay(); 
                } else {
                    // if the book is borrowed then its due date is available 
                    $expectedAvailability = $dueDate;
                }
            } 

            
            $reserve->expected_availability = $expectedAvailability;

            return $reserve;
        });

        $reservedBooks = $reservedBooksWithAvailability;

        // dd($reservedBooks);

        return view('member.reserved_books', compact('reservedBooks'));
    }

    public function borrowReservedBooks(Borrow $borrow) {
        $borrow->update([
            'type' => 'borrow',
            'borrow_date' => null,
            'status' => 'pending'
        ]);
        $borrow->user->book_limit -= 1;
        $borrow->user->save();
        return response()->json(['success' => true]);
    }
}
