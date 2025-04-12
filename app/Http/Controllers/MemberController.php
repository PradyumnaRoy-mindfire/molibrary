<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    //
    public function books()  {
        return view('member.books');
    }
    public function showDashboard()  {
        return view('member.dashboard');
    }
    
}
