<?php

namespace App\Exceptions;

use App\Repositories\IpGlobalRepository;
use App\Models\IpLocked;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    private $message = '';
    private $systemError = false;
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

        if (!IpGlobalRepository::isLockedIp()) {
            $this->message = 'Aplikasi sedang dalam perbaikan. Kami akan segera kembali!';
            return $this->renderMaintenanceMode($request);
        }

        if ($this->isMaintenanceMode()) {
            $this->message = 'Aplikasi sedang dalam pemeliharaan. Kami akan segera kembali.';
            return $this->renderMaintenanceMode($request);
        }

        if ($exception->getCode() != 0) {
            if ($exception instanceof NotFoundHttpException) {
                $status = 404;
                $message = $exception->getMessage() ?: 'Halaman yang Anda cari tidak ditemukan.';
                return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);

            } elseif ($exception instanceof ModelNotFoundException) {
                $status = 404;
                $message = $exception->getMessage() ?: 'Data yang Anda minta tidak ditemukan.';
                return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);

            } elseif ($exception instanceof AuthenticationException) {
                $status = 401;
                $message = $exception->getMessage() ?: 'Anda harus login untuk mengakses halaman ini.';
                return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);

            } elseif ($exception instanceof ValidationException) {
                $status = 422;
                $errors = $exception->validator->errors()->all();
                return response()->view('errors.error', ['message' => $errors], $status);

            } elseif ($exception instanceof AccessDeniedHttpException) {
                $status = 403;
                $message = $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.';
                return response()->view('errors.error', ['message' => $message, 'status' => $status], $status);
            }

        } else {
            $this->message = $exception->getMessage() ?: 'Aplikasi sedang dalam perbaikan. Kami akan segera kembali.';
            $this->systemError = true;
            return $this->renderMaintenanceMode($request);
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

        if ($this->message) {
            $message = $this->message;
        }

        $data = [
            'message' => $message,
            'title_error' => (($this->systemError) ? 'System Error' : 'Under Maintenance')
        ];

        return response()->view('errors.maintenance', $data, 503);
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