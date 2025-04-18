<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Library;
use App\Models\Librarian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        }

        return redirect()->route('login')->with('success', 'Registration successful. Please wait for approval.');
    }

    public function loginUser(LoginRequest $request)
    {
        $loginData = $request->validated();
        
        if (Auth::attempt($loginData)) {
            $user = Auth::user();
            return redirect()->route('dashboard');
        }
        else {
            return back()->with('loginFailed', 'Invalid credentials, please try again.');
        } 
    }

    public function profileUpdate(Request $request) {
        $updatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|max:10|min:10',
        ]);
        $User = User::where('id',Auth::id())->Update([
            'name' => $updatedData['name'],
            'phone' => $updatedData['phone'],
            'address' => $request->address,
        ]);

        if (!$User) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        return back()->with('profileUpdateSuccess', 'Profile updated successfully!');
    }

    public function passwordUpdate(Request $request) {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);
    
        $user = Auth::user();
    
        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
    
        // Update the new password
        $user->password = $request->new_password;
        
        // $user->save();
    
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
