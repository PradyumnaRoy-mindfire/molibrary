<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Fine;
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
                $popularLibraryCount = Borrow::distinct('library_id')->count('library_id');
                $fines = Fine::all()->sum('amount');
                return view('super_admin.dashboard', compact('fines', 'popularLibraryCount'));
            case 'library_admin':
                return view('library_admin.dashboard');
            case 'member':
                $fines = $user->fines->sum('amount');
                $library = count(Library::where('status', 'open')->get());
                if ($user->membership && now()->lt($user->membership->end_date)) {
                    $membership = $user->membership;
                } else {
                    $membership = null;
                }
                return view('member.dashboard', compact('membership', 'library', 'fines'));
            case 'librarian':
                return view('librarian.dashboard');
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
}
