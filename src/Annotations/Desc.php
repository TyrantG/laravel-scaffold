<?php

namespace TyrantG\LaravelScaffold\Annotations;

use Attribute;

/**
 * 描述
 *
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Desc
{
    /**
     * @param  string  $value  描述
     */
    public function __construct(string $value)
    {
    }
}
