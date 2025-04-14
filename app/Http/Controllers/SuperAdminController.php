<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    //
    public function showDashboard()
    {
        return view('super_admin.dashboard');
    }

    public function assignAdminForm(Library $library)
    {
        return view('super_admin.assign_admin', ['library' => $library]);
    }

            //assigning admin to a library
    public function assignAdminToLibrary(Request $request, Library $library)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('role', $request->role);
                })
            ],
            'phone' => 'required|max:10|min:10',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => 'library_admin'
        ]);

        if ($user) {
            Library::where('id', $library->id)->update([
                'admin_id' => $user->id,
            ]);

            return redirect()->route('manage.library')->with('assignAdminSuccess', 'Admin assigned successfully!');
        } else {
            return back()->with('assignAdminError', 'User not found.');
        }
    }
            //showing all members' ndetails, 
    public function showAllMembers()
    {
        //  member stats with fines and borrow counts
        $members = DB::table('users')
            ->leftJoin('borrows', 'users.id', '=', 'borrows.users_id')
            ->leftJoin('fines', 'borrows.id', '=', 'fines.borrow_id')
            ->where('users.role', 'member')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COALESCE(SUM(fines.amount - fines.discount), 0) as total_fine'),
                DB::raw('COUNT(DISTINCT borrows.id) as borrowed_books_count')
            )
            ->groupBy('users.id', 'users.name')
            ->paginate(10);

        // borrow category counts per member
        $categoryCounts = DB::table('users')
            ->join('borrows', 'users.id', '=', 'borrows.users_id')
            ->join('books', 'borrows.book_id', '=', 'books.id')
            ->join('categories', 'books.category_id', '=', 'categories.id')
            ->where('users.role', 'member')
            ->select(
                'users.id as user_id',
                'categories.name as category_name',
                DB::raw('COUNT(*) as borrow_count')
            )
            ->groupBy('users.id', 'categories.name')
            ->get();

        // Group top 3 preferred categories per user
        $topCategories = $categoryCounts->groupBy('user_id')->map(function ($group) {
            return $group
                ->sortByDesc('borrow_count')
                ->take(3)
                ->pluck('category_name')
                ->values()
                ->toArray();
        });

        //  preferred categories to each member
        $members->getCollection()->transform(function ($member) use ($topCategories) {
            $member->preferred_categories = $topCategories[$member->id] ?? [];
            return $member;
        });
        return view('super_admin.all_members', compact('members'));
    }

    
}
