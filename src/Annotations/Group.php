<?php

namespace TyrantG\LaravelScaffold\Annotations;

use Attribute;

/**
 * 分组
 *
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Group
{
    /**
     * @param  string  $value  分组
     */
    public function __construct(string $value)
    {
    }
}
