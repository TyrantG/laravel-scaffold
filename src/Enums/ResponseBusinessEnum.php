<?php

namespace TyrantG\LaravelScaffold\Enums;

enum ResponseBusinessEnum: string
{
    case RESPONSE_SUCCESS = '请求成功';
    case BAD_REQUEST = '参数错误';
    case AUTHENTICATION_FAILED = '鉴权失败';
    case ROUTE_NOT_FOUND = '路由不存在';
    case MODEL_NOT_FOUND = '数据未找到';
    case REQUEST_TIMESTAMP_EXCEED = '时间戳校验失败';
    case REQUEST_SIGNATURE_ERROR = '接口签名校验失败';
    case DATA_HAS_EXISTS = '数据已存在';
    case SERVER_ERROR = '服务器错误';
}
