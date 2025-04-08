<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\Librarian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    //
    public function showLoginForm() {
        return view('user.login');
    }
    public function showRegisterForm() {
        $libraries = Library::all();
        return view('user.register',['libraries'=> $libraries]);
    }
    public function profile() {
        return view('user.profile',['users'=>Auth::user()]);
    }

    public function registerUser(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required',
                        'email',
                        Rule::unique('users')->where(function ($query) use ($request) {
                            return $query->where('role', $request->role);
                        })],
            'phone' => 'required|max:10|min:10',
            'password' => 'required|min:6',
            'role' => 'required|in:librarian,member',
            'library_id' => 'required_if:role,librarian|exists:libraries,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => $request->role
        ]);
        if ($request->role === 'librarian') {
            $data = new Librarian();
            $data->user_id = $user->id;
            $data->library_id = $request->library_id;
            $data->status = 'pending';
            $data->save();
        }

        return redirect()->route('login')->with('success', 'Registration successful. Please wait for approval.');
    }

    public function loginUser(Request $request) {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:librarian,member,library_admin,super_admin',
        ]);
        
        if(Auth::attempt($loginData)) {
           return redirect()->route('profile');
        }
        else {
            return redirect()->route('login')->with('loginFailed',"Credential is not valid...Try Again");
        }
        

    }

    public function logout(Request $request) {
        Auth::logout();
 
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}
