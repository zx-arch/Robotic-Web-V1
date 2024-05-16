<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Repositories\IpGlobalRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\IpGlobal;
use App\Models\IpLocked;

class CheckBlockedIP
{
    protected $ipGlobalRepository;

    public function __construct(IpGlobalRepository $ipGlobalRepository)
    {
        $this->ipGlobalRepository = $ipGlobalRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $ip = session('myActivity.ip_address') ?? $request->ip();

            $ipGlobal = $this->ipGlobalRepository->findByNetwork($ip);

            $checkLock = IpLocked::where('network', $ip)->first();

            if (!$checkLock) {
                if ($ipGlobal && $ipGlobal->is_blocked) {

                    Auth::logout();
                    session(['blocked_ip' => true]);

                    // Jika IP terblokir, redirect ke halaman tertentu
                    return redirect()->route('form.login')->withErrors(['message' => 'IP address anda telah di-block oleh pengelola!']);
                }
            }

            if (session()->has('blocked_ip')) {
                session()->forget('blocked_ip');
            }

            return $next($request);

        } catch (\Throwable $e) {

            Auth::logout();
            return redirect()->route('form.login')->withErrors(['message' => 'Gagal akses login ke sistem! ' . $e->getMessage()]);

        }
    }
}