<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    //
    public function showDashboard()  {
        return view('super_admin.dashboard');
    }
}
