<?php

namespace TyrantG\LaravelScaffold\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use TyrantG\LaravelScaffold\Enums\ResponseBusinessEnum;
use TyrantG\LaravelScaffold\Enums\ResponseCodeEnum;

class BusinessException extends Exception
{
    public function render(ResponseCodeEnum $error, string $message): JsonResponse
    {
        return response()->json(http_result([], $error->value, $message ?: ResponseBusinessEnum::{$error->name}->value));
    }
}
