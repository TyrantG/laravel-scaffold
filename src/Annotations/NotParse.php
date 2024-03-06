<?php

namespace TyrantG\LaravelScaffold\Annotations;

use Attribute;

/**
 * 标记不解析的控制器/方法
 *
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class NotParse
{
    public function __construct()
    {
    }
}
