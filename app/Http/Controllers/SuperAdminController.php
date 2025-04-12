<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

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
}
