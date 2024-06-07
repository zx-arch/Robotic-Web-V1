<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class GuestAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna terotentikasi
        if (!Auth::check()) {
            return redirect()->route('form.login')->withErrors(['message' => 'Silakan login terlebih dahulu']);
        }

        // Cek apakah pengguna adalah pengurus
        if (Auth::user()->role !== 'guest') {
            return redirect()->route('form.login')->withErrors(['message' => 'Anda tidak memiliki izin untuk mengakses halaman ini']);
        }

        if (Auth::user()->role == 'guest' && Auth::user()->status != 'active') {
            return redirect()->route('form.login')->withErrors(['message' => 'Mohon menunggu konfirmasi account anda dari admin.']);
        }

        return $next($request);
    }
}