<?php

namespace TyrantG\LaravelScaffold\Annotations;

use Attribute;

/**
 * 请求头
 *
 * @Annotation
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Header extends ParamBase
{
    /**
     * mock
     */
    public string $mock;

    /**
     * @param  string  $name  字段名
     * @param  string  $type  字段类型
     * @param  string  $desc  字段名称
     * @param  bool  $require  是否必须
     * @param  string|array  $ref  引用注解/模型
     * @param  string  $md  Md文本内容
     * @param  string|array  $field  指定Ref引入的字段
     * @param  string|array  $withoutField  排除Ref引入的字段
     * @param  string  $mock  Mock规则
     */
    public function __construct(
        string $name = '',
        string $type = '',
        bool $require = false,
        string|array $ref = '',
        string $desc = '',
        string $md = '',
        string|array $field = '',
        string|array $withoutField = '',
        string $mock = ''
    ) {
    }
}
