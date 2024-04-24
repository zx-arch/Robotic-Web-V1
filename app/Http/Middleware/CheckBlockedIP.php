<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ListIP;
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
            if (session()->has('myActivity.ip_address') || session('myActivity.ip_address')) {
                $listIP = ListIP::where('network', session('myActivity.ip_address'))->first();

            } else {
                $listIP = ListIP::where('network', $_SERVER['REMOTE_ADDR'])->first();
            }

            $checkLock = IpLocked::where('network', $listIP->network)->first();

            if (!$checkLock) {
                if ($listIP && $listIP->is_blocked) {

                    Auth::logout();
                    session_unset();
                    session()->invalidate();
                    session()->regenerateToken();
                    session()->flush();
                    session(['blocked_ip' => true]);

                    // Jika IP terblokir, redirect ke halaman tertentu
                    return redirect()->route('form.login')->withErrors(['message' => 'IP address anda telah di-block oleh pengelola!']);
                }
            }

            return $next($request);

        } catch (\Throwable $e) {

            Auth::logout();
            session_unset();
            session()->invalidate();
            session()->regenerateToken();
            session()->flush();
            session(['blocked_ip' => true]);

            return redirect()->route('form.login')->withErrors(['message' => 'Gagal akses login ke sistem! ' . $e->getMessage()]);
        }

    }
}