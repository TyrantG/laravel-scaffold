<?php

namespace TyrantG\LaravelScaffold\Annotations;

use Attribute;

/**
 * 作者
 *
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Author
{
    /**
     * @param  string  $value  作者名称
     */
    public function __construct(string $value)
    {
    }
}
