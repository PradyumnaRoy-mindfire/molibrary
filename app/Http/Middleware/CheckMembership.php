<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // dd($user->membership);

        if (!$user->membership || now()->gt($user->membership->end_date)) {
            if($user->membership) {
                $user->membership->has_access = 0;
                $user->membership->save();
            }
            session()->flash('no_membership', true);
            
            return back();
        }

        return $next($request);
    }
}
