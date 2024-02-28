<?php

namespace Shigabutdinoff\JsonRelationBuilder\Services;

use \Illuminate\Database\Eloquent\Builder;
use Shigabutdinoff\JsonRelationBuilder\Traits\JsonRelationTrait;

class JsonRelationBuilderService extends Builder
{
    use JsonRelationTrait;
}
