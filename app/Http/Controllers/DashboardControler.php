<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Fine;
use App\Models\Librarian;
use App\Models\Library;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\select;

class DashboardControler extends Controller
{
    //
    public function showDashboard()
    {
        $user = Auth::user();
        $role = $user->role;
        switch ($role) {
            case 'super_admin':
                $topBookCount = Book::withTrashed()->count('id');
                $popularLibraryCount = Borrow::distinct('library_id')->count('library_id');
                $fines = Fine::all()->sum('amount');
                return view('super_admin.dashboard', compact('fines', 'popularLibraryCount', 'topBookCount'));


            case 'library_admin':
                $library = Library::where('admin_id', Auth::id())->first();
                $overDueBookCount = Borrow::where('status', 'borrowed')
                    ->where('library_id', $library->id)
                    ->where('due_date', '<', now())
                    ->with(['book', 'user'])
                    ->count();

                $lowStockBookCount = Book::where('library_id', $library->id)
                    ->where('total_copies', '<', 6)
                    ->withCount('borrows')
                    ->count();

                //library total fine 
                $borrows = Borrow::where('library_id', $library->id)->get();
                $totalFine = $borrows->sum(function ($borrow) {
                    $fine = $borrow->fine;
                    return $fine ? $fine->amount : 0;
                });

                return view('library_admin.dashboard', compact('library', 'overDueBookCount', 'lowStockBookCount', 'totalFine'));


            case 'member':
                $fines = $user->fines->where('status', 'pending')->sum('amount');

                $library = count(Library::where('status', 'open')->get());
                if ($user->membership && now()->lt($user->membership->end_date)) {
                    $membership = $user->membership;
                    $book_limit = $user->book_limit;
                } else {
                    $book_limit = 0;
                    $membership = null;
                }
                return view('member.dashboard', compact('membership', 'library', 'fines', 'book_limit'));


            case 'librarian':
                $librarian = Librarian::where('user_id', Auth::id())->first();

                $mostBorrowedBooksCount = $librarian->library->mostBorrowedBooks()->count();

                $lowStockBooksCount = Book::where('library_id', $librarian->library->id)
                    ->where('total_copies', '<', 6)
                    ->withCount('borrows')
                    ->get()->count();

                $borrows = Borrow::where('library_id', $librarian->library->id)->get();
                
                $totalFine = $borrows->sum(function ($borrow) {
                    $fine = $borrow->fine;
                    return $fine ? $fine->amount : 0;
                });

                return view('librarian.dashboard', compact('librarian', 'mostBorrowedBooksCount','lowStockBooksCount','totalFine'));
            default:
                abort(403, 'Unknown user role');
        }
    }
    //member dashboard cards
    public function showMembershipDetails()
    {
        $user = Auth::user();
        $membership = $user->membership;
        return view('member.dashboard_cards.membership', compact('membership'));
    }

    public function showMembershipFeatures()
    {
        return view('member.dashboard_cards.membership_features');
    }
    public function activeLibrary()
    {
        $libraries = Library::withCount('books')
            ->where('status', 'open')
            ->get();
        return view('member.dashboard_cards.active_library', compact('libraries'));
    }

    public function outstandingFines()
    {
        $books = Auth::user()->fines->where('status', 'pending');

        return view('member.dashboard_cards.outstanding_fines', compact('books'));
    }

    //superadmin dashboard cards

    public function topChoicesBooks()
    {
        $books = Book::with(['author', 'category'])
            ->withTrashed()
            ->withCount('borrows')
            ->orderBy('borrows_count', 'desc')
            // ->limit(5)
            ->get();

        // dd($books);
        return view('super_admin.dashboard_cards.top_choices', compact('books'));
    }

    public function totalRevenue()
    {
        $payments = Fine::with(['borrow'])->get();
        // dd($payments);
        return view('super_admin.dashboard_cards.total_revenue', compact('payments'));
    }

    public function popularLibraries()
    {
        $libraries = Library::select('libraries.*')
            ->join('users', 'libraries.admin_id', '=', 'users.id')
            ->whereIn('libraries.id', function ($query) {
                $query->select('library_id')
                    ->from('borrows')
                    ->distinct();
            })
            ->withCount('books')
            ->with('admin:id,name,email')
            ->get();
        // dd($libraries);
        return view('super_admin.dashboard_cards.popular_libraries', compact('libraries'));
    }


    //library admin dashboard cards
    public function overdueBooks()
    {
        $libraryId = Library::where('admin_id', auth()->user()->id)->value('id');

        $overdueBooks = Borrow::where('status', 'borrowed')
            ->where('library_id', $libraryId)
            ->where('due_date', '<', now())
            ->with(['book', 'user'])
            ->get();

        return view('library_admin.dashboard_cards.overdue_books', compact('overdueBooks'));
    }

    public function lowStock()
    {
        $library = Library::where('admin_id', auth()->user()->id)->first();
        $lowStockBooks = Book::where('library_id', $library->id)
            ->where('total_copies', '<', 6)
            ->withCount('borrows')
            ->get();
        return view('library_admin.dashboard_cards.low_stock', compact('lowStockBooks'));
    }

    public function totalFine()
    {
        $library = Library::where('admin_id', auth()->user()->id)->first();
        $fines = Fine::whereIn('borrow_id', function ($query) use ($library) {
            $query->select('id')
                ->from('borrows')
                ->where('library_id', $library->id);
        })
            ->with(['borrow.user', 'borrow.book'])  // Eager load relationships
            ->get();

        return view('library_admin.dashboard_cards.total_revenue', compact('fines'));
    }

    //librarian
    public function mostBorrowedBooks(Library $library)
    {
        $books = $library->mostBorrowedBooks()->with(['author', 'category'])->get();

        return view('librarian.dashboard_cards.most_borrowed_books', compact('books'));
    }

    public function libraryLowStock(Library $library)
    {
        $lowStockBooks = Book::where('library_id', $library->id)
            ->where('total_copies', '<', 6)
            ->withCount('borrows')
            ->get();
        return view('librarian.dashboard_cards.low_stock_books', compact('lowStockBooks'));
    }

    public function totalLibraryFine(Library $library)
    {
        $fines = Fine::whereIn('borrow_id', function ($query) use ($library) {
            $query->select('id')
                ->from('borrows')
                ->where('library_id', $library->id);
        })
            ->with(['borrow.user', 'borrow.book'])  
            ->get();

        return view('librarian.dashboard_cards.total_revenue', compact('fines'));
    }
}
