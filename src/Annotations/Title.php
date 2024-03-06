<?php

namespace TyrantG\LaravelScaffold\Annotations;

use Attribute;

/**
 * 标题
 *
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Title
{
    /**
     * @param  string  $value  接口名称
     */
    public function __construct(string $value)
    {
    }
}
