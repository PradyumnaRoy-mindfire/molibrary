<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignAdminRequest;
use App\Http\Requests\EditAdminRequest;
use App\Models\Library;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

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
    public function assignAdminToLibrary(AssignAdminRequest $request, Library $library)
    {
        $request->validated();

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

    public function showAllMembers(Request $request, DataTables $dataTables)
    {
        if ($request->ajax()) {
            $users = User::with(['borrows.book.category', 'borrows.fine'])
                ->where('role', 'member');
    
            return DataTables::of($users)
                ->addColumn('borrowed_books_count', function ($user) {
                    return $user->borrows->count();
                })
                ->addColumn('total_fine', function ($user) {
                    return $user->borrows->sum(function ($borrow) {
                        return ($borrow->fine->amount ?? 0) - ($borrow->fine->discount ?? 0);
                    });
                })
                ->addColumn('preferred_categories', function ($user) {
                    $categoryCount = [];
    
                    foreach ($user->borrows as $borrow) {
                        $category = $borrow->book->category->name ?? null;
                        if ($category) {
                            $categoryCount[$category] = ($categoryCount[$category] ?? 0) + 1;
                        }
                    }
    
                    return collect($categoryCount)
                        ->sortDesc()
                        ->take(3)
                        ->keys()
                        ->implode(', ');
                })
    
                //  orderable & searchable
                ->orderColumn('borrowed_books_count', function ($query, $order) {
                    $query->withCount('borrows')->orderBy('borrows_count', $order);
                })
                //fine
                ->orderColumn('total_fine', function ($query, $order) {
                  
                    $query->selectRaw('users.*, (SELECT SUM(fines.amount - fines.discount)
                            FROM borrows
                            LEFT JOIN fines ON borrows.id = fines.borrow_id
                            WHERE borrows.users_id = users.id) as total_fine_calc')
                        ->orderBy('total_fine_calc', $order);
                })
                ->filterColumn('preferred_categories', function ($query, $keyword) {
                    $query->whereHas('borrows.book.category', function ($q) use ($keyword) {
                        $q->where('categories.name', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['preferred_categories']) 
                ->make(true);
        }
    
        return view('super_admin.all_members');
    }

    public function editLibraryAdminDetails(User $admin) {
        return view('super_admin.edit_library_admin',compact('admin'));
    }

    public function updateLibraryAdminDetails(EditAdminRequest $request, User $admin) {
        $request->validated();
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return redirect()->route('library.admins')->with('libraryAdminUpdated', 'Admin details updated successfully!');
    }

    
}
