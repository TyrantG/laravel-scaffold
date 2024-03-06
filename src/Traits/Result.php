<?php

namespace TyrantG\LaravelScaffold\Traits;

use Illuminate\Http\JsonResponse;
use Throwable;
use TyrantG\LaravelScaffold\Enums\ResponseCodeEnum;

trait Result
{
    public static function success(mixed $data = [], ?string $message = null): array
    {
        return ResponseCodeEnum::RESPONSE_SUCCESS->make($data, $message);
    }

    public static function error(ResponseCodeEnum $error = ResponseCodeEnum::SERVER_ERROR, ?string $message = null): array
    {
        return $error->make([], $message);
    }

    public function forbidden(): void
    {
        abort(403);
    }

    public function notFound(): void
    {
        abort(404);
    }
}
