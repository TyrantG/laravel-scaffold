<?php

namespace TyrantG\LaravelScaffold\Annotations;

use Attribute;

/**
 * 请求类型
 *
 * @Annotation
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Method
{
    /**
     * @param  string  $value  请求类型
     */
    public function __construct(string $value)
    {
    }
}
