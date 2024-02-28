<?php

namespace Shigabutdinoff\JsonRelationBuilder\Traits;

use Illuminate\Database\Eloquent\Builder;

trait JsonRelationTrait
{
    /**
     * @var bool
     */
    private bool $flag = true;

    /**
     * @return void
     */
    private function registerDynamicMethods()
    {
        if ($this->flag) {
            foreach (get_class_methods(JsonRelationTrait::class) as $method) {
                $callable = $this->$method(...);
                Builder::macro($method, function (...$args) use ($callable, $method) {
                    $value = $callable(...$args);
                    if ($value instanceof \Closure) {
                        return $value($this);
                    }

                    return $this;
                });
            }

            $this->flag = false;
        }
    }

    /**
     * @param string $related
     * @param string|NULL $name
     * @return $this
     */
    public function hasOneMacro(string $related, string $name = NULL)
    {
        $this->registerDynamicMethods();

        if (is_null($name)) $name = $related::getModel()->getTable();
        Builder::macro($name, function () use($related) {
            return $this->getModel()->hasOne($related);
        });

        return $this;
    }

    /**
     * @param $relation
     * @param $column
     * @param $values
     * @param $boolean
     * @param $not
     * @return \Closure
     */
    public function whereRelationJsonContains($relation, $column, $values, $boolean = 'and', $not = false)
    {
        return function (Builder $query) use ($relation, $column, $values, $boolean, $not) {
            return $query
                ->whereRelation($relation, function (Builder $query) use ($column, $values, $boolean, $not) {
                    $query->where(function (Builder $query) use ($column, $values, $boolean, $not) {
                        foreach (is_array($values) ? $values : [$values] as $value) {
                            $query->whereJsonContains($column, $value, $boolean, $not);
                        }
                    });
                });
        };
    }

    /**
     * @param $relation
     * @param $column
     * @param $values
     * @param $boolean
     * @param $not
     * @return \Closure
     */
    public function orWhereRelationJsonContains($relation, $column, $values, $boolean = 'and', $not = false)
    {
        return function (Builder $query) use ($relation, $column, $values, $boolean, $not) {
            return $query
                ->orWhereRelation($relation, function (Builder $query) use ($column, $values, $boolean, $not) {
                    $query->where(function (Builder $query) use ($column, $values, $boolean, $not) {
                        foreach (is_array($values) ? $values : [$values] as $value) {
                            $query->whereJsonContains($column, $value, $boolean, $not);
                        }
                    });
                });
        };
    }

    /**
     * @param $relation
     * @param $column
     * @param $value
     * @param $boolean
     * @return \Closure
     */
    public function whereRelationJsonDoesntContain($relation, $column, $value, $boolean = 'and')
    {
        return $this->whereRelationJsonContains($relation, $column, $value, $boolean, true);
    }

    /**
     * @param $relation
     * @param $column
     * @param $value
     * @param $boolean
     * @return \Closure
     */
    public function orWhereRelationJsonDoesntContain($relation, $column, $value, $boolean = 'and')
    {
        return $this->orWhereRelationJsonContains($relation, $column, $value, $boolean, true);
    }
}
