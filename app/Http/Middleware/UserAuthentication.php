<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna terotentikasi
        if (!Auth::check()) {
            return redirect()->route('form.login')->withErrors(['message' => 'Silakan login terlebih dahulu']);
        }

        // Cek apakah pengguna adalah user
        if (Auth::user()->role !== 'user') {
            return redirect()->route('form.login')->withErrors(['message' => 'Anda tidak memiliki izin untuk mengakses halaman ini']);
        }

        if (Auth::user()->role == 'user' && Auth::user()->status != 'active') {
            return redirect()->route('form.login')->withErrors(['message' => 'Mohon menunggu konfirmasi account anda dari admin.']);
        }

        return $next($request);
    }
}