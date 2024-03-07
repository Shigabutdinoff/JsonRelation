<?php

namespace Shigabutdinoff\JsonRelation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shigabutdinoff\JsonRelation\Traits\JsonRelationTrait;


class JsonRelation
{
    public static function addToModel(Builder|Model|string $query)
    {
        $query = $query::getModel();

        $query_builder = 'Query' . basename(get_class($query->getQuery()));
        $code = sprintf('class %1$s extends %2$s {use %3$s;}', $query_builder, get_class($query->getQuery()), JsonRelationTrait::class);
        eval($code);

        $new_query_builder = (new $query_builder($query->getQuery()->connection, $query->getQuery()->grammar, $query->getQuery()->processor))->from($query->getTable());

        $e_builder = basename(Builder::class);
        $code = sprintf('class %1$s extends %2$s {use %3$s;}', $e_builder, Builder::class, JsonRelationTrait::class);
        eval($code);

        $model = basename(get_class($query));
        $code = sprintf('class %1$s extends %2$s {use %3$s;}', $model, get_class($query), JsonRelationTrait::class);
        eval($code);

        $new_model = (new $model())->setQuery($new_query_builder)->getModel();

        return (new $e_builder($new_query_builder))->setModel($new_model);
    }
}
