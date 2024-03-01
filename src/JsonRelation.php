<?php

namespace Shigabutdinoff\JsonRelation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shigabutdinoff\JsonRelation\Traits\JsonRelationTrait;

class JsonRelation
{
    public static function addToModel(Builder|Model|string $query)
    {
        $query = is_string($query) ? $query::getModel() : $query;
        $model = $query instanceof Builder ? $query->getModel() : $query;
        $dynamic = basename($model::class);
        $code = sprintf('class %1$s extends %2$s {use %3$s;}', $dynamic, $model::class, JsonRelationTrait::class);
        eval($code);
        $query->setModel(new $dynamic());
        return $query;
    }
}
