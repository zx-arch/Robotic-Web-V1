<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCookieTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // Cek waktu kedaluwarsa cookie user_email
        if ($request->cookie('user_email') && strtotime($request->cookie('user_email')) == time()) {
            Auth::logout(); // Logout pengguna
            return redirect()->intended(route('form.login'))->withErrors(['message' => 'Sesi Anda telah berakhir. Silakan login kembali']);
        }

        if (!$request->cookie('user_email')) {
            Auth::logout(); // Logout pengguna
            return redirect()->intended(route('form.login'))->withErrors(['message' => 'Sesi Anda telah berakhir. Silakan login kembali']);
        }

        return $next($request);
    }
}