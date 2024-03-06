<?php

namespace TyrantG\LaravelScaffold\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use TyrantG\LaravelScaffold\Enums\ResponseBusinessEnum;
use TyrantG\LaravelScaffold\Enums\ResponseCodeEnum;

class BusinessException extends Exception
{
    public ResponseCodeEnum $error = ResponseCodeEnum::SERVER_ERROR;
    public $message = '';

    public function __construct(ResponseCodeEnum $error = ResponseCodeEnum::SERVER_ERROR, string $message = '', \Throwable $previous = null, int $code = 0)
    {
        $this->error = $error;
        $this->message = $message;

        parent::__construct($message, $code, $previous);
    }

    public function render(): JsonResponse
    {
        return response()->json(http_result(
            [],
            $this->error->value,
            $this->message ?: ResponseBusinessEnum::{$this->error->name}->value,
            'FAILED'
        ));
    }
}
