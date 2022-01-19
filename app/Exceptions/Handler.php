<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Http\Traits\HelperTrait;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    use HelperTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

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

        $this->renderable(function (Throwable $e) {
            if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect('/login');
            } /*elseif ($e instanceof \Illuminate\Database\QueryException) {
                return back()->with('error', $this->serverErrorMessage())->withInput();
            } else {

            }*/
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return redirect('/login');
    }
}
