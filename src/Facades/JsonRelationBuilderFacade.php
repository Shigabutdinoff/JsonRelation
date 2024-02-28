<?php

namespace Shigabutdinoff\JsonRelationBuilder\Facades;

use Illuminate\Support\Facades\Facade;

class JsonRelationBuilderFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Shigabutdinoff\JsonRelationBuilder\Services\JsonRelationBuilderService::class;
    }
}
