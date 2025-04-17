<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;

class DashboardControler extends Controller
{
    //
    public function showDashboard() {
        $role = Auth::user()->role;
        switch ($role) {
            case 'super_admin':
                return view('super_admin.dashboard');
            case 'library_admin':
                return view('library_admin.dashboard');
            case 'member':
                return view('member.dashboard');
            case 'librarian':
                return view('librarian.dashboard');
            default:
                abort(403, 'Unknown user role');
        }
    }
}
