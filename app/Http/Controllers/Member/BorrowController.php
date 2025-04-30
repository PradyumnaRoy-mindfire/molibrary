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

        if($user->book_limit == 0) {
            return redirect()->route('borrowing.history')->with('error', 'You have reached your book limit!!');
        }

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

        return redirect()->route('browse.books')->with('reserve_success', 'Book Reserved Successfully!!');
    }

    

    public function borrowReservedBooks(Borrow $borrow)
    {
        $borrow->update([
            'type' => 'borrow',
            'borrow_date' => null,
            'status' => 'pending'
        ]);

        $borrow->user->book_limit -= 1;
        $borrow->user->save();
        
        return redirect()->route('borrowing.history')->with('success', 'Borrow Request sent Successfully!!');
    }
}
