<?php

namespace TyrantG\LaravelScaffold\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use TyrantG\LaravelScaffold\Enums\ResponseBusinessEnum;
use TyrantG\LaravelScaffold\Enums\ResponseCodeEnum;

class CommonException extends Exception
{
    public function render(string $message): JsonResponse
    {
        return response()->json(http_result([], ResponseCodeEnum::SERVER_ERROR->value, $message ?: ResponseBusinessEnum::SERVER_ERROR->value));
    }
}
