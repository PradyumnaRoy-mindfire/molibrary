<?php

namespace App\Http\Controllers;

use App\Events\LibrarianRegistered;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Library;
use App\Models\Librarian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        if (Auth::user()) {
            return redirect()->route('dashboard');
        }
        return view('user.login');
    }
    public function showRegisterForm()
    {
        $libraries = Library::all();
        return view('user.register', ['libraries' => $libraries]);
    }
    public function profile()
    {
        return view('user.profile', ['users' => Auth::user()]);
    }


    public function registerUser(RegisterRequest $request)
    {
        $request->validated();

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

            //trigger new librarian request event
            event(new LibrarianRegistered($request->name));
        }

        return redirect()->route('login')->with('registerSuccess', 'Registration successful...'); 
    }

    public function checkEmailRoles(Request $request)
    {
        $users = User::where('email', $request->email)->get();
        $roles = $users->pluck('role')->unique()->values();

        return response()->json([
            'roles' => $roles
        ]);
    }


    public function loginUser(LoginRequest $request)
    {
        $loginData = $request->validated(); 

            //to check the librarian is approved or not 
        $user = User::where('email', $request->email)->get();
        $roles = $user->pluck('role')->unique()->values();
        $rolesCount = $roles->count();

            //if the user have multiple roles and login as librarian
        if($rolesCount > 1 && $request->role == 'librarian') {
            $librarianUser = $user->firstWhere('role', 'librarian');
            if($librarianUser->librarian->status == 'pending') {
                
                return back()->with('loginFailed', 'You are not approved by library admin,wait for approval!!');
                
            }

        } else {
            //if the user have single role(librarian)
            if ($user->count() > 0 && $user[0]->role == 'librarian' && $user[0]->librarian->status == 'pending') {

                return back()->with('loginFailed', 'You are not approved by library admin,wait for approval!!');

            }
        }
        
        if (Auth::attempt($loginData)) {

            return redirect()->route('dashboard')->with('logged_in_success','You are logged in successfully!!');

        } else {
            
            return back()->with('loginFailed', 'Invalid credentials, please try again.');
        }
    }


    public function profileUpdate(ProfileUpdateRequest $request)
    {
        $updatedData = $request->validated();

        $User = User::where('id', Auth::id())->Update([
            'name' => $updatedData['name'],
            'phone' => $updatedData['phone'],
            'address' => $request->address,
        ]);

        if (!$User) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        return back()->with('profileUpdateSuccess', 'Profile updated successfully!');
    }

    public function passwordUpdate(PasswordUpdateRequest $request)
    {
        $request->validated();

        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update the new password
        $user->password = $request->new_password;

        return back()->with('success', 'Password updated successfully!');
    }

    public function initiateResetFormData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'role' => 'required|in:librarian,member,library_admin,super_admin',
        ]);

        if ($validator->fails()) {
            return response()->json(['exists' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $user = User::where('email', $data['email'])
            ->where('role', $data['role'])
            ->first();

        return response()->json(['exists' => !!$user]);
    }

    public function sendOtp(Request $request)
    {
        $otp = $request->otp;

        return response()->json([
            'success' => true,
            'message' => 'New OTP sent successfully'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
