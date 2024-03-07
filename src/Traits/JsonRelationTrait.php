<?php

namespace Shigabutdinoff\JsonRelation\Traits;

use Illuminate\Database\Eloquent\Builder;

trait JsonRelationTrait
{
//    /**
//     * @return void
//     */
//    public function __construct()
//    {
//        foreach (get_class_methods(JsonRelationTrait::class) as $method) {
//            $callable = $this->$method(...);
//            Builder::macro($method, function (...$args) use ($callable, $method) {
//                $value = $callable(...$args);
//                if ($value instanceof \Closure) {
//                    return $value($this);
//                }
//
//                return $this;
//            });
//        }
//    }

    /**
     * @param string $related
     * @param string|NULL $name
     * @return $this
     */
    public function setHasOneRelation(string $related, string $name = NULL)
    {

        $builder = $this->setModel($this->getModel()->setRelation('roles', $this->getModel()->hasOne($related)->getModel()))/*->getModel()*/;

        var_dump(get_class($builder));
        //var_dump($builder->with('roles')->get());

//        var_dump($builder->with('roles')->get());

        return /*$this->with([], $this->getModel()->hasOne($related))->get()*/;

//        return $this->getModel()
//            ->where('id', 1)
//            ->setRelation('roles', $this->getModel()->find(1)->hasOne($related)->first())
//            ->first();

//        if (is_null($name)) $name = (new $related)->getTable();
//        return $this->getModel()->get()->map(function ($model) use ($name, $related) {
//            return $model->setRelation($name, $model->hasOne($related)->first());
//        });
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
