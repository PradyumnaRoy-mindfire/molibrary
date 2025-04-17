<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Library;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // $books = DB::table('books')
        //     ->join('libraries', 'books.library_id', '=', 'libraries.id')
        //     ->join('authors', 'books.author_id', '=', 'authors.id')
        //     ->join('categories', 'books.category_id', '=', 'categories.id')
        //     ->where('libraries.admin_id', $adminId)
        //     ->select(
        //         'books.*',
        //         'authors.name as author_name',
        //         'categories.name as category_name',
        //         'libraries.name as library_name',
        //         'libraries.id as library_id' 
        //     )
        //     ->orderBy('books.created_at', 'desc')
        //     ->paginate(6); 

        // $categories = DB::table('categories')->get();

        $books = Book::with(['author', 'category', 'library'])
            ->whereHas('library', function ($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })
            ->select('books.*')
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        $categories = Category::all();

        return view('library_admin.manage_books', compact('books', 'categories'));

    }

    public function approveLibrarians()
    {

        $library = Library::where('admin_id', Auth::id())->first();

        if (!$library) {
            return response()->json(['error' => 'Library not found for this admin.'], 404);
        }

        // Step 2: Get users with role=librarian and their librarian status is pending
        $librarians = User::where('role', 'librarian')
            ->whereHas('librarian', function ($query) use ($library) {
                $query->where('library_id', $library->id);
                //   ->where('status', 'pending');
            })
            ->with(['librarian' => function ($query) use ($library) {
                $query->where('library_id', $library->id)
                    ->where('status', 'pending');
            }])
            ->select(['id', 'name', 'email', 'created_at'])->get();
        return view('library_admin.approve_librarians', compact('librarians'));
    }
}
