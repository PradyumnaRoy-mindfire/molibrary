<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LibraryRequest;
use App\Models\Library;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Assign;

class LibraryController extends Controller
{
    //
    public function createLibraryForm()
    {
        return view('super_admin.add_library');
    }

    public function createLibrary(LibraryRequest $request)
    {
        //validations are in the library request 
        $request->validated();

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
        $libraries = Library::all();
        return view('super_admin.manage_library', compact('libraries'));
    }

    //showing the library admins
    public function showLibraryAdmins()
    {
        $admins = User::where('role', 'library_admin')
            ->with('library.librarians')
            ->paginate(10);

        return view('super_admin.all_library_admins', compact('admins'));
    }

    //edit a library admin

}
