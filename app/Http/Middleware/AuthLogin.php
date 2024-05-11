<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Roles;

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
            Cookie::queue(Cookie::forget('user_email'));
            return redirect()->intended(route('form.login'))->withErrors(['message' => 'Silakan login terlebih dahulu']);
        }

        if (Roles::where('name', Auth::user()->role)->first()) {
            return redirect()->intended(route('form.login'))->withErrors(['message' => 'Unauthorized action.']);
        }

        return $next($request);
    }
}