<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();
        if ($user->role !== $role) {
            return redirect('/login')->with('error', 'You do not have the required role to access this page.');
        }

        return $next($request);
    }
}