<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use TyrantG\LaravelScaffold\Model;

if (! function_exists('uuid')) {
    /**
     * 生成 uuid v4
     * */
    function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}

if (! function_exists('is_url')) {
    /**
     * 判断字符串是否是 url
     * */
    function is_url(string $url): bool
    {
        $preg = "/https?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&+%]*/i";

        return preg_match($preg, $url);
    }
}

if (! function_exists('get_web_image')) {
    /**
     * 下载网络图片
     * */
    function get_web_image(string $url): bool|string
    {
        try {
            $client = new Client(['verify' => false]);

            return $client->request('get', $url)->getBody()->getContents();
        } catch (GuzzleException $e) {
            Log::error('获取网络图片失败', (array) $e);

            return false;
        }
    }
}

if (! function_exists('alphabet')) {
    /**
     * 字母表
     */
    function alphabet(bool $capital = false): string
    {
        return $capital ? 'ABCDEFGHIJKLOMNOPQRSTUVWXYZ' : 'abcdefghijklmnopqrstuvwxyz';
    }
}

if (! function_exists('randomKeys')) {
    /**
     * 生成随机字符串(含数字、大小写字母)
     * */
    function randomKeys(int $length): string
    {
        $key = '';
        $pattern = '1234567890'.alphabet().alphabet(true);
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern[mt_rand(0, mb_strlen($pattern) - 1)];
        }

        return $key;
    }
}

if (! function_exists('modelUpdate')) {
    /**
     * @see 用于模型更新,使用save取代update方法
     *
     * @param  Model  $model  待更新模型
     * @param  array  $params  新数据
     * @param  bool  $strict  严格模式：true为按模型的fillable更新，false为全部更新，可能存在字段不存在
     */
    function modelUpdate(Model $model, array $params, bool $strict = true): Model
    {
        foreach ($params as $column => $value) {
            if ($strict) {
                if (in_array($column, $model->getFillable())) {
                    $model->{$column} = $value;
                }
            } else {
                $model->{$column} = $value;
            }
        }
        $model->save();

        return $model;
    }
}

if (! function_exists('addZeros')) {
    /**
     * 数字补0
     */
    function addZeros(int $number, int $length): string
    {
        $numberString = (string) $number;
        if (strlen($numberString) >= $length) {
            return $numberString;
        } else {
            return sprintf("%0{$length}d", $number);
        }
    }
}

if (! function_exists('adminAuthCheck')) {
    /**
     * 判断后台登录
     */
    function adminAuthCheck(): bool
    {
        return \Auth::guard('admin')->check();
    }
}

if (! function_exists('fillArrayKey')) {
    /**
     * 将数组填充至fillable
     */
    function fillArrayKey(array $inputArray, array $fillableArray): array
    {
        $result = array_intersect_key($inputArray, array_flip($fillableArray));

        return array_merge(array_fill_keys($fillableArray, null), $result);
    }
}

if (! function_exists('getDistance')) {
    /**
     * 根据两点间的经纬度计算距离
     */
    function getDistance($lat1, $lng1, $lat2, $lng2): float
    {
        $earthRadius = 6367; //approximate radius of earth in meters

        /*
            Convert these degrees to radians
            to work with the formula
        */

        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;

        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;

        /*
            Using the
            Haversine formula
            http://en.wikipedia.org/wiki/Haversine_formula
            calculate the distance
        */

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance, 4);
    }
}
