<?php

namespace TyrantG\LaravelScaffold\Annotations;

abstract class ParamBase
{
    /**
     * 类型
     *
     * @Enum({"string", "integer", "int", "boolean", "array", "double", "object", "tree",
     *                  "file","float","date","time","datetime"})
     */
    public string $type;

    /**
     * 默认值
     */
    public string $default;

    /**
     * 描述
     */
    public string $desc;

    /**
     * 为tree类型时指定children字段
     */
    public string $childrenField = '';

    /**
     * 为tree类型时指定children字段说明
     */
    public string $childrenDesc = 'children';

    /**
     * 为array类型时指定子节点类型
     *
     * @Enum({"string", "int", "boolean", "array", "object"})
     */
    public string $childrenType = '';

    /**
     * 指定引入的字段
     */
    public string $field;

    /**
     * 指定从引入中过滤的字段
     */
    public string $withoutField;

    /**
     * 说明md内容
     */
    public string $md;

    /**
     * 必须
     */
    public bool $require = false;

    /**
     * 引入
     */
    public string|array $ref;

    /**
     * 子参数
     */
    public array $children;
}
