<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    //
    public function createLibraryForm() {
        return view('super_admin.add_library');
    }
    
    public function createLibrary(Request $request) {
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
        return back()->with('libraryStored','Library added Successfully..');
    }
}
