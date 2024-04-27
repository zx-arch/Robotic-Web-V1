<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IpGlobal;
use App\Models\IpLocked;

class CheckBlockedIP
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
        try {
            $ip = session('myActivity.ip_address') ?? $_SERVER['REMOTE_ADDR'];

            $ipGlobal = ipGlobal::where('network', $ip)->first();

            $checkLock = IpLocked::where('network', $ip)->first();

            if (!$checkLock) {
                if ($ipGlobal && $ipGlobal->is_blocked) {

                    Auth::logout();
                    session(['blocked_ip' => true]);

                    // Jika IP terblokir, redirect ke halaman tertentu
                    return redirect()->route('form.login')->withErrors(['message' => 'IP address anda telah di-block oleh pengelola!']);
                }
            }

            return $next($request);

        } catch (\Throwable $e) {

            Auth::logout();
            session(['blocked_ip' => true]);

            return redirect()->route('form.login')->withErrors(['message' => 'Gagal akses login ke sistem! ' . $e->getMessage()]);
        }

    }
}