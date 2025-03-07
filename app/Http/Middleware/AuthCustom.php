<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class CustomAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        // Cek session 'isLoggedIn'
        if (!Session::has('isLoggedIn')) {
            return redirect('/login')->withErrors(['message' => 'Anda harus login terlebih dahulu.']);
        }

        return $next($request);
    }
}