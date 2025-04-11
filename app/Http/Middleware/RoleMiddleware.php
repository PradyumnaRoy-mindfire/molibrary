<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = FacadesAuth::user();
        $roles = ['super_admin','library_admin','librarian','member'];
        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized acccess.You can not enter this page...');
        }
        return $next($request);
    }
}
