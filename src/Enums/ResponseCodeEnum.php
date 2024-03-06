<?php

namespace TyrantG\LaravelScaffold\Enums;

enum ResponseCodeEnum: int
{
    case RESPONSE_SUCCESS = 0;
    case BAD_REQUEST = 4000;
    case AUTHENTICATION_FAILED = 4001;
    case ROUTE_NOT_FOUND = 4040;
    case MODEL_NOT_FOUND = 4041;
    case REQUEST_TIMESTAMP_EXCEED = 4101;
    case REQUEST_SIGNATURE_ERROR = 4103;
    case DATA_HAS_EXISTS = 4433;
    case SERVER_ERROR = 5000;
    case API_SERVER_ERROR = 5500;

    public function make(mixed $data = null, ?string $message = null): array
    {
        return match ($this) {
            self::RESPONSE_SUCCESS => self::response($data, self::RESPONSE_SUCCESS, $message ?: '请求成功'),
            self::BAD_REQUEST => self::response($data, self::BAD_REQUEST, $message ?: '参数错误', 'FAILED'),
            self::AUTHENTICATION_FAILED => self::response($data, self::AUTHENTICATION_FAILED, $message ?: '鉴权失败', 'FAILED'),
            self::ROUTE_NOT_FOUND => self::response($data, self::ROUTE_NOT_FOUND, $message ?: '路由不存在', 'FAILED'),
            self::MODEL_NOT_FOUND => self::response($data, self::MODEL_NOT_FOUND, $message ?: '数据未找到', 'FAILED'),
            self::REQUEST_TIMESTAMP_EXCEED => self::response($data, self::REQUEST_TIMESTAMP_EXCEED, $message ?: '时间戳校验失败', 'FAILED'),
            self::REQUEST_SIGNATURE_ERROR => self::response($data, self::REQUEST_SIGNATURE_ERROR, $message ?: '接口签名校验失败', 'FAILED'),
            self::DATA_HAS_EXISTS => self::response($data, self::DATA_HAS_EXISTS, $message ?: '数据已存在', 'FAILED'),
            self::SERVER_ERROR => self::response($data, self::SERVER_ERROR, $message ?: '服务器错误', 'FAILED'),
            self::API_SERVER_ERROR => self::response($data, self::API_SERVER_ERROR, $message ?: '第三方服务错误', 'FAILED'),
        };
    }

    public static function code(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function response($data, $code, $message, $result = 'SUCCESS'): array
    {
        return [
            'state' => [
                'code' => $code,
                'message' => $message,
                'result' => $result,
            ],
            'data' => $data,
        ];
    }
}
