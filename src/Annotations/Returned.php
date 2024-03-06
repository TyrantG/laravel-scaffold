<?php

namespace TyrantG\LaravelScaffold\Annotations;

use Attribute;

/**
 * 返回参数
 *
 * @Annotation
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Returned extends ParamBase
{
    /**
     * 是否替换全局响应体中的参数
     */
    public bool $replaceGlobal = false;

    /**
     * @param  string  $name  字段名
     * @param  string  $type  字段类型
     * @param  bool  $require  是否必须
     * @param  string|int|bool  $default  默认值
     * @param  string|array  $ref  引用注解/模型
     * @param  string  $table  引用数据表
     * @param  string|array  $field  指定Ref引入的字段
     * @param  string|array  $withoutField  排除Ref引入的字段
     * @param  string  $desc  字段名称
     * @param  string  $md  Md文本内容
     * @param  array  $children  子参数
     * @param  string  $childrenField  为tree类型时指定children字段
     * @param  string  $childrenDesc  为tree类型时指定children字段说明
     * @param  string  $childrenType  为array类型时指定子节点类型
     * @param  bool  $replaceGlobal  是否替换全局响应体参数
     * @param  mixed  ...$attrs
     */
    public function __construct(
        string $name = '',
        string $type = '',
        bool $require = false,
        string|int|bool $default = '',
        string|array $ref = '',
        string $table = '',
        string|array $field = '',
        string|array $withoutField = '',
        string $desc = '',
        string $md = '',
        string $mock = '',
        array $children = [],
        string $childrenField = '',
        string $childrenDesc = 'children',
        string $childrenType = '',
        bool $replaceGlobal = false,
        ...$attrs
    ) {
    }
}
