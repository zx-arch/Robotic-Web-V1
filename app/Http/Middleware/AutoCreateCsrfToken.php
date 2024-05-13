<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class AutoCreateCsrfToken extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $response = parent::handle($request, $next);

        // Periksa jika ada upaya untuk menghapus token CSRF dari sesi
        if ($request->session()->has('_token') && $request->session()->isStarted() && $request->session()->token() !== $request->input('_token')) {
            // Jika ada upaya penghapusan token CSRF, set kembali token CSRF ke nilai awal
            $request->session()->put('_token', $request->session()->token());
        }

        // Mengatur header Strict-Transport-Security (HSTS) untuk memaksa klien selalu menggunakan HTTPS
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        return $response;
    }
}