<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'seller') {
            return redirect('/login')->withErrors(['role' => 'You must be a seller to access this page.']);
        }

        return $next($request);
    }
}