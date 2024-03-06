<?php

namespace TyrantG\LaravelScaffold\Listeners;

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use TyrantG\LaravelScaffold\Traits\Result;

class ResponseHandler
{
    use Result;

    public function __construct()
    {

    }

    public function handle(RequestHandled $event): void
    {
        $response = $event->response;

        if ($response instanceof JsonResponse) {
            $exception = $response->exception;

            if ($response->getStatusCode() == Response::HTTP_OK && ! $exception) {
                $response->setData(self::success($response->getData()));
            }
        }
    }
}
