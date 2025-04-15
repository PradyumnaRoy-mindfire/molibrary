<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB ;

class LibraryAdminController extends Controller
{
    //
    public function showDashboard()
    {
        return view('library_admin.dashboard');
    }

    public function showLibrarybooks()
    {
        $adminId = Auth::id();

        $books = DB::table('books')
            ->join('libraries', 'books.library_id', '=', 'libraries.id')
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->join('categories', 'books.category_id', '=', 'categories.id')
            ->where('libraries.admin_id', $adminId)
            ->select(
                'books.*',
                'authors.name as author_name',
                'categories.name as category_name',
                'libraries.name as library_name',
                'libraries.id as library_id' 
            )
            ->orderBy('books.created_at', 'desc')
            ->paginate(6); 

        $categories = DB::table('categories')->get();

        return view('library_admin.manage_books', compact('books', 'categories'));
    }

    public function approveLibrarians() {
        return view('library_admin.approve_librarians');
    }
}
