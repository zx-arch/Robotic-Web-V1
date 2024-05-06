<?php

namespace App\Exceptions;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Throwable;

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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        /**
         * Use Maintenance System.
         *
         * Rename file downs to down in storage/framework
         * Rename file maintenances.php menjadi maintenance.php in storage/framework
         */
        //dd($exception->getMessage());
        if ($this->isMaintenanceMode()) {
            return $this->renderMaintenanceMode($request);
        }

        if ($exception instanceof NotFoundHttpException) {
            // Pengecualian 404: Halaman tidak ditemukan
            $status = 404;
            $message = $exception->getMessage() ?: 'Halaman yang Anda cari tidak ditemukan.';
            return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);

        } elseif ($exception instanceof ModelNotFoundException) {
            // Pengecualian ketika model tidak ditemukan
            $status = 404;
            $message = $exception->getMessage() ?: 'Data yang Anda minta tidak ditemukan.';
            return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);

        } elseif ($exception instanceof AuthenticationException) {
            // Pengecualian ketika autentikasi gagal
            $status = 401;
            $message = $exception->getMessage() ?: 'Anda harus login untuk mengakses halaman ini.';
            return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);

        } elseif ($exception instanceof ValidationException) {
            // Pengecualian ketika validasi gagal
            $status = 422;
            $errors = $exception->validator->errors()->all();
            return response()->view('errors.error', ['message' => $errors], $status);

        } elseif ($exception instanceof AccessDeniedHttpException) {
            $status = 403;
            $message = $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.';
            return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);

        }

        return parent::render($request, $exception);
    }

    /**
     * Determine if the application is in maintenance mode.
     *
     * @return bool
     */
    protected function isMaintenanceMode(): bool
    {
        return file_exists(storage_path('framework/down'));
    }

    /**
     * Render the maintenance mode page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function renderMaintenanceMode($request)
    {
        $message = 'Aplikasi sedang dalam pemeliharaan. Kami akan segera kembali.';
        return response()->view('errors.maintenance', ['message' => $message], 503);
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $e
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}