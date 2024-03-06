<?php

return [
    'request_logger' => [
        'enabled' => env('REQUEST_LOGGER_ENABLED', true),
        // 是否记录非json格式的响应的数据
        'is_record_not_json' => true,
        // 设置不记录日志的url
        'except_url' => [
            'admin/logs*',
        ],
        // 设置不记录响应日志的url
        'except_record_response_url' => [

        ],
        // 设置不记录的荷载项
        'except_payload' => [
            '_token', '_method',
        ],
        // 设置不记录日志的请求头
        'except_header' => [
            // 官方
            'accept', 'accept-encoding', 'accept-language', 'authorization',
            'access-control-allow-credentials', 'access-control-allow-headers', 'access-control-allow-methods', 'access-control-allow-origin',
            'access-control-expose-headers', 'access-control-max-age', 'access-control-request-headers', 'access-control-request-method',
            'cache-control', 'charset', 'connection', 'content-length', 'content-type_except', 'cookie',
            'host', 'origin', 'pragma', 'referer',
            'sec-ch-ua', 'sec-ch-ua-mobile', 'sec-ch-ua-platform', 'sec-fetch-dest', 'sec-fetch-mode', 'sec-fetch-site',
            'upgrade-insecure-requests', 'user-agent', 'x-forwarded-for', 'x-forwarded-host', 'x-forwarded-port', 'x-forwarded-proto', 'x-requested-with',
            // 自定义
        ],
    ],

    'async_logger' => [
        'enabled' => env('ASYNC_LOG_ENABLED', true),
        'connection' => env('ASYNC_LOG_CONNECTION', config('queue.default')),
        'queue' => env('ASYNC_LOG_QUEUE', 'default'),
    ],

    'exception_handler' => [
        'enabled' => env('EXCEPTION_HANDLER_ENABLED', true),
        'handler' => 'TyrantG\\LaravelScaffold\\Exceptions\\Handler',
    ],
];
