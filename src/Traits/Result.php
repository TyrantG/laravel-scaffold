<?php

namespace TyrantG\LaravelScaffold\Traits;

use TyrantG\LaravelScaffold\Enums\ResponseBusinessEnum;
use TyrantG\LaravelScaffold\Enums\ResponseCodeEnum;
use TyrantG\LaravelScaffold\Exceptions\CommonException;

trait Result
{
    public static function success(mixed $data = [], ?string $message = null): array
    {
        return http_result($data, ResponseCodeEnum::RESPONSE_SUCCESS->value, $message ?: ResponseBusinessEnum::RESPONSE_SUCCESS->value);
    }

    /**
     * @throws CommonException
     */
    public static function error(ResponseCodeEnum $error = ResponseCodeEnum::RESPONSE_SUCCESS, ?string $message = null): array
    {
        throw new CommonException($error, $message);
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
