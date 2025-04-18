<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibraryController extends Controller
{
    //
    public function createLibraryForm()
    {
        return view('super_admin.add_library');
    }

    public function createLibrary(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required|max:100',
            'status' => 'required'
        ]);
        $data = new Library();
        $data->name = $request->name;
        $data->status = $request->status;
        $data->location = $request->location;
        $data->save();
        return back()->with('libraryStored', 'Library added Successfully..');
    }

    // showing libraries
    public function showLibraries()
    {
        $libraries = Library::leftJoin('users', 'libraries.admin_id', '=', 'users.id')
            ->select('libraries.*', 'users.name as admin_name', 'users.email as admin_email')
            ->get();
        return view('super_admin.manage_library', ['libraries' => $libraries]);
    }

    //showing the library admins
    public function showLibraryAdmins()
    {
        $admins = DB::table('libraries')
            ->join('users', 'libraries.admin_id', '=', 'users.id')
            ->leftJoin('librarians', 'libraries.id', '=', 'librarians.library_id')
            ->select(
                'users.id as admin_id',
                'users.name as admin_name',
                'libraries.name as library_name',
                DB::raw('COUNT(librarians.id) as librarian_count')
            )
            ->groupBy('users.id', 'users.name', 'libraries.name')
            ->paginate(10);

        return view('super_admin.all_library_admins',compact('admins'));
    }

    //edit a library admin
    public function editLibraryAdminDetails() {
        
    }
}
