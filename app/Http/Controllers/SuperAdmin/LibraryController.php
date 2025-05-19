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
    public function createLibraryForm($library = null)
    {
        if ($library) {
            $library = Library::findOrFail($library);
        }
        return view('super_admin.add_edit_library', compact('library'));
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
        return redirect()->route('manage.library')->with('libraryToast', 'Library added Successfully..');
    }

    public function updateLibrary(LibraryRequest $request, Library $library)
    {
        //validations are in the library request 
        $request->validated();
        $library->update($request->all());
       
        $library->save();

        return redirect()->route('manage.library')->with('libraryToast', 'Library Updated Successfully..');
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
