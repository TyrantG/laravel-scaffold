<?php

namespace TyrantG\LaravelScaffold;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use TyrantG\LaravelScaffold\Traits\HasDateTimeFormatter;

class Model extends BaseModel
{
    use HasDateTimeFormatter;

    public static function booted(): void
    {
        /**
         * 关联排序
         * */
        Builder::macro('orderByWith',
            function ($relation, $column, $direction = 'asc'): Builder {
                /** @var Builder $this */
                if (is_string($relation)) {
                    $relation = $this->getRelationWithoutConstraints($relation);
                }

                return $this->orderBy(
                    $relation->getRelationExistenceQuery(
                        $relation->getRelated()->newQueryWithoutRelationships(),
                        $this,
                        $column
                    ),
                    $direction
                );
            });
    }
}
