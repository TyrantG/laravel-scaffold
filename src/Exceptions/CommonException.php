<?php

namespace TyrantG\LaravelScaffold\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use TyrantG\LaravelScaffold\Enums\ResponseBusinessEnum;
use TyrantG\LaravelScaffold\Enums\ResponseCodeEnum;

class CommonException extends Exception
{
    public $message = '';

    public function __construct(string $message = '', ?\Throwable $previous = null, int $code = 0)
    {
        $this->message = $message;
        parent::__construct($message, $code, $previous);
    }

    public function render(): JsonResponse
    {
        return response()->json(http_result(
            [],
            ResponseCodeEnum::SERVER_ERROR->value,
            $this->message ?: ResponseBusinessEnum::SERVER_ERROR->value,
            'FAILED'
        ));
    }
}
