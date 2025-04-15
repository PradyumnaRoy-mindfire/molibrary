<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    //

    public function addBookForm(Library $library)
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('library_admin.add_edit_book', compact('authors', 'categories','library'));
    }

    public function storeBook(Request $request, Library $library)
    {
        $request->validate([
            'title' => 'required',
            'isbn' => '|required|min:10|max:13'
        ]);
    }

    public function editBook(Book $book)
    {   
        $authors = Author::all();
        $categories = Category::all();
        return view('library_admin.add_edit_book', compact('book', 'authors', 'categories'));
    }
}
