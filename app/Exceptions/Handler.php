<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\PagesExceptions;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            // Pengecualian 404: Halaman tidak ditemukan
            $status = 404;
            $message = $exception->getMessage() ?: 'Halaman yang Anda cari tidak ditemukan.';
            return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);

        } elseif ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            // Pengecualian ketika model tidak ditemukan
            $status = 404;
            $message = $exception->getMessage() ?: 'Data yang Anda minta tidak ditemukan.';
            return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);

        } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            // Pengecualian ketika autentikasi gagal
            $status = 401;
            $message = $exception->getMessage() ?: 'Anda harus login untuk mengakses halaman ini.';
            return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);

        } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
            // Pengecualian ketika validasi gagal
            $status = 422;
            $errors = $exception->validator->errors()->all();
            return response()->view('errors.error', ['message' => $errors], $status);
        }

        // Penanganan pengecualian lainnya
        return parent::render($request, $exception);
    }


    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}