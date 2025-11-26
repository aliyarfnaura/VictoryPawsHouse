<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsCustomer
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && strtolower(Auth::user()->role) !== 'admin') {
            return $next($request); 
        }
        
        if (Auth::check() && strtolower(Auth::user()->role) === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak diizinkan mengakses area Pelanggan.');
        }

        return redirect('/')->with('error', 'Akses ditolak.');
    }
}