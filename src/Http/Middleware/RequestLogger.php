<?php

namespace TyrantG\LaravelScaffold\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RequestLogger
{
    public function handle(Request $request, Closure $next): Response|JsonResponse|RedirectResponse
    {
        if (! config('laravel-scaffold.request_logger.enabled') || $this->getExceptUrl($request)) {
            return $next($request);
        }

        $request_data = [
            'url' => $this->getFullUrl($request),
            'method' => $this->getRequestMethod($request),
            'ip' => $this->getClientIp($request),
            'ua' => $this->getUa($request),
            'payload' => $this->getRequestPayload($request),
            'file' => $this->getClearRequestFile($request),
            'header' => $this->getClearRequestHeader($request),
            'time' => 0,
            'mem' => '0B',
            'code' => 0,
            'response' => '""',
        ];

        $is_except_record_response_data_url = $this->getExceptRecordResponseDataUrl($request);
        if ($is_except_record_response_data_url) {
            async_log('request', $this->dataFormat($request_data));
        }

        $start = microtime(true);
        $response = $next($request);
        $end = microtime(true);

        if (! $is_except_record_response_data_url) {
            $request_data['time'] = bcmul(bcsub($end, $start, 6), 1000, 2);
            $request_data['mem'] = $this->getUsageMemory();
            $request_data['code'] = $this->getHttpCode($response);
            $request_data['response'] = $this->responseFormat($request, $response);

            async_log('request', $this->dataFormat($request_data));
        }

        return $response;
    }

    /**
     * 获取不记录日志的url
     */
    private function getExceptUrl(Request $request): bool
    {
        $blacklist = array_filter(config('laravel-scaffold.request_logger.except_url'));
        if (! $blacklist) {
            return false;
        }

        foreach ($blacklist as $every_blacklist) {
            if ($request->is($every_blacklist)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取不记录响应日志的url
     */
    private function getExceptRecordResponseDataUrl(Request $request): bool
    {
        $blacklist = array_filter(config('laravel-scaffold.request_logger.except_record_response_url'));
        if (! $blacklist) {
            return false;
        }

        foreach ($blacklist as $every_blacklist) {
            if ($request->is($every_blacklist)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取完整的请求路径
     */
    private function getFullUrl(Request $request): string
    {
        return urldecode($request->fullUrl());
    }

    /**
     * 获取请求方式
     *
     * @param Request $request
     * @return string
     *
     * @other    由于laravel存在_method覆盖机制，若有括号，则括号内的为真正的请求方式
     */
    private function getRequestMethod(Request $request): string
    {
        $real_method = $_SERVER['REQUEST_METHOD'] ?? '';

        //防止乱传参导致的错误
        try {
            $laravel_method = $request->method();
        } catch (\Exception $exception) {
            $laravel_method = $real_method;
        }

        if ($real_method === $laravel_method) {
            return $real_method;
        }

        return "{$laravel_method}({$real_method})";
    }

    /**
     * 获取客户端的IP
     *
     * @param  Request  $request
     * @return string
     */
    private function getClientIp(Request $request): string
    {
        return $request->getClientIp();
    }

    /**
     * 获取用户代理
     *
     * @param Request $request
     *
     * @return string
     */
    private function getUa(Request $request): string
    {
        return $request->header('user-agent') ?? '""';
    }

    /**
     * 获取请求荷载，包含x-www-form-urlencoded、multipart/form-data、application/json、application/xml等纯文本荷载数据
     */
    private function getRequestPayload(Request $request): array
    {
        if ($request->method() === 'GET') {
            return [];
        }
        $except = collect($request->query())->keys()->merge(config('laravel-scaffold.request_logger.except_payload'))->filter();
        $input = collect($request->input())->except($except)->map(function ($val) {
            if (is_null($val)) {
                return '';
            }

            return $val;
        })->toArray();

        if ($input) {
            return $input;
        }

        $raw = $request->getContent();

        if (str_contains($request->header('content-type'), 'xml')) {
            if (! $raw) {
                return [];
            }
            if (! $this->isXml($raw)) {
                return [$raw];
            }

            return json_decode(json_encode(simplexml_load_string(str_replace(["\r", "\n"], '', $raw))), true);
        }

        return array_filter([$raw]);
    }

    /**
     * 获取简洁的文件上传数据
     */
    private function getClearRequestFile(Request $request): array
    {
        return collect($request->allFiles())->map(function ($val) {
            if (is_array($val)) {
                $res = collect($val)->map(function ($v) {
                    return $v->getClientOriginalName();
                });
            } else {
                $res = $val->getClientOriginalName();
            }

            return $res;
        })->toArray();
    }

    /**
     * 获取干净的请求头
     */
    private function getClearRequestHeader(Request $request): array
    {
        return collect($request->header())->except(array_filter(config('laravel-scaffold.request_logger.except_header')))->map(function ($every_header, $key) {
            return (count($every_header) === 1) ? collect($every_header)->values()->first() : $every_header;
        })->toArray();
    }

    /**
     * 通过token获取user_id
     */
    private function getIdWithToken(Request $request): int
    {
        $token = $request->header('authorization');
        if (! $token) {
            return 0;
        }

        $payload = (explode('.', $token)[1]) ?? null;
        if (is_null($payload)) {
            return 0;
        }

        $json = base64_decode($payload);
        $arr = json_decode($json, true);
        if (is_null($arr)) {
            return 0;
        }

        return $arr['sub'] ?? 0;
    }

    /**
     * 获取状态码
     *
     * @param JsonResponse|Response $response
     *
     * @return string|array
     */
    private function getHttpCode(Response|JsonResponse $response): array|string
    {
        return $response->getStatusCode();
    }

    /**
     * 格式化响应数据
     */
    private function responseFormat(Request $request, Response|JsonResponse $response): array|string
    {
        if ($response instanceof JsonResponse) {
            return collect($response->getData())->toArray();
        }

        if (! config('laravel-scaffold.request_logger.is_record_not_json')) {
            return '""';
        }

        if ($response instanceof Response) {
            return $response->getContent();
        }

        return '""';
    }

    /**
     * 格式化数组并转换为字符串
     *
     * @param  $request_data  array
     *
     * @return string
     */
    private function dataFormat(array $request_data): string
    {
        $str = "\n";
        foreach ($request_data as $k => $v) {
            $k = str_pad($k, 9, ' ', STR_PAD_RIGHT);
            $v = is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v;
            $str .= "{$k}: {$v}\n";
        }

        return $str;
    }

    /**
     * 获取程序占用的内存
     *
     * @return string
     */
    private function getUsageMemory(): string
    {
        $bytes = memory_get_usage();
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes /= pow(1024, ($i = floor(log($bytes, 1024))));

        return round($bytes, 2).' '.$units[$i];
    }

    /**
     * 判断是否是xml
     *
     * @param  $str  string 要判断的xml数据
     * @return bool
     */
    private function isXml($str)
    {
        libxml_use_internal_errors(true);
        simplexml_load_string($str);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        return ! $errors;
    }
}
