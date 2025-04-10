<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ForgotPasswordController extends Controller
{
    //
    public function forgotPassword()
    {
        return view('user.forgot_password', ['users' => Auth::user()]);
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
        if($user) {
            // $otp = rand(10000,99999);
            $otp = 12345;
            session(['otp' => $otp]);
            // Mail::to($user->email)->send(new SendMail($this->otp));
        }
        return response()->json(['exists' => !!$user]);
    }

    public function verifyOtp(Request $request)
    {
        
        $entered_otp = $request->otp;
        
        if ($entered_otp == session('otp')) {
            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP'
            ]);
            
        }
        
    }
}
