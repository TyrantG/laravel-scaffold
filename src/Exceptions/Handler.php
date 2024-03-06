<?php

namespace TyrantG\LaravelScaffold\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use TyrantG\LaravelScaffold\Enums\ResponseCodeEnum;
use TyrantG\LaravelScaffold\Traits\Result;

class Handler extends ExceptionHandler
{
    use Result;

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
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        /**
         * 模型未找到
         * */
        if ($e instanceof ModelNotFoundException) {
            return self::error(ResponseCodeEnum::MODEL_NOT_FOUND);
        }

        /**
         * 请求验证器
         * */
        if ($e instanceof ValidationException) {
            return self::error(ResponseCodeEnum::BAD_REQUEST, $e->errorBag);
        }

        /**
         * 路由未找到
         * */
        if ($e instanceof NotFoundHttpException && $request->expectsJson()) {
            return self::error(ResponseCodeEnum::ROUTE_NOT_FOUND, URL::full());
        }

        /**
         * 鉴权失败
         * */
        if ($e instanceof AuthenticationException && $request->expectsJson()) {
            return self::error(ResponseCodeEnum::AUTHENTICATION_FAILED);
        }

        return parent::render($request, $e);
    }
}
