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
    public function borrowConfirmation(Book $book){
        $library = Library::where('id',$book->library_id)->first();
        $borrowCount = Borrow::where('book_id',$book->id)->count('id');
        return view('member.borrow_confirmation',compact('book','library','borrowCount'));
    }

    public function borrowBooks(Book $book ){  
        $book->borrows()->create([
            'library_id' => $book->library_id,
            'users_id' => Auth::user()->id,
            'type' => 'borrow',
            'status' => 'pending',
            'borrow_date' => now(),
            'due_date' => now()->addMinutes(120),
        ]);
        return redirect()->route('browse.books');
    }
}
