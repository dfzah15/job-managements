<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JobdeskMiddleware
{
    public function handle(Request $request, Closure $next, $slug): Response
    {
        // Cek apakah user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // ADMIN bisa akses SEMUA jobdesk tanpa cek permission
        if (Auth::user()->role == 'admin') {
            return $next($request);
        }

        // Cek apakah user punya akses ke jobdesk ini
        if (!Auth::user()->hasJobdesk($slug)) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke jobdesk ini.');
        }

        return $next($request);
    }
}