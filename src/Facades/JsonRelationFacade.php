<?php

namespace Shigabutdinoff\JsonRelation\Facades;

use Illuminate\Support\Facades\Facade;

class JsonRelationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Shigabutdinoff\JsonRelation\Services\JsonRelationService::class;
    }
}
