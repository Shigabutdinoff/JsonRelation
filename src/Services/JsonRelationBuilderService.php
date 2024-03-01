<?php

namespace Shigabutdinoff\JsonRelationBuilder\Services;

use Illuminate\Database\Eloquent\Builder;
use Shigabutdinoff\JsonRelationBuilder\Traits\JsonRelationTrait;

class JsonRelationBuilderService
{
    public function model(string $parentClass)
    {
        $trait = JsonRelationTrait::class;
        $dynamicClass = basename($parentClass);
        $code = "class $dynamicClass extends $parentClass {use $trait;}";
        eval($code);
        return new $dynamicClass();
    }

    public function builder(Builder $builder)
    {
        $trait = JsonRelationTrait::class;
        $parentClass = $builder::class;
        $dynamicClass = basename($parentClass);
        $code = "class $dynamicClass extends $parentClass {use $trait;}";
        eval($code);
        return (new $parentClass/*->setQuery*/($builder->getQuery()))
            ->setModel($builder->getModel())
            ->setEagerLoads($builder->getEagerLoads())
            ->setBindings($builder->getBindings());
    }
}
