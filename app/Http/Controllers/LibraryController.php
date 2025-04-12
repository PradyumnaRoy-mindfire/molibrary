<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Http\Request;

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
        $libraries = \App\Models\Library::leftJoin('users', 'libraries.admin_id', '=', 'users.id')
            ->select('libraries.*', 'users.name as admin_name', 'users.email as admin_email')
            ->get();
        return view('super_admin.manage_library', ['libraries' => $libraries]);
    }
}
