<?php

namespace TyrantG\LaravelScaffold\Annotations;

use Attribute;

/**
 * 接口Url
 *
 * @Annotation
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Url
{
    /**
     * @param  string  $value  接口Url
     */
    public function __construct(string $value)
    {
    }
}
