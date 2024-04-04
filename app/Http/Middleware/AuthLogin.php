<?php

namespace App\Http\Middleware;

session_start();

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna terautentikasi
        if (!Auth::check()) {
            session_unset();
            return redirect()->route('form.login')->withErrors(['message' => 'Silakan login terlebih dahulu']);
        }

        return $next($request);
    }
}