<?php

namespace App\Models;

use App\Collection\BaseCollection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Class Base
 * @author xiaoze <zeasly@live.com>
 * @package App\Models
 * @property  Carbon create_time
 * @property  Carbon update_time
 * @method $this find($id)
 * @method $this findOrFail($id)
 * @method $this paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
 * @method int increment($column, $amount = 1, array $extra = [])
 * @method int decrement($column, $amount = 1, array $extra = [])
 */
abstract class Base extends Model
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    const DELETED_AT = 'delete_time';

    protected $guarded = [
        'id',
        'create_time',
        'update_time',
        'delete_time',
    ];

    protected $hidden = ['delete_time'];

    /**
     * save 成功后需要处理的任务
     * @var array
     */
    public $savedJobs = [];

    /**
     * save 成功后需要抛出的事件
     * @var array
     */
    public $savedEvents = [];

    public static $columnsName = [];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('defaultSort',
            function (Builder $builder) {
                $builder->orderBy($builder->getModel()->table . '.' . $builder->getModel()->primaryKey, 'desc');
            });
    }


    /**
     * 根据id初始化
     *
     * @param $id
     * @return $this
     */
    public static function initById($id, $columns = ['*'], $withTrashed = false)
    {
        if ($withTrashed) {
            return static::withTrashed()->findOrFail($id, $columns);
        } else {
            return static::findOrFail($id, $columns);
        }
    }

    /**
     * 根据id获取对象
     *
     * @param $id
     * @return $this
     */
    public static function getById($id, $columns = ['*'], $withTrashed = false)
    {
        if ($withTrashed) {
            return static::withTrashed()->find($id, $columns);
        } else {
            return static::find($id, $columns);
        }
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param array $models
     * @return BaseCollection
     */
    public function newCollection(array $models = [])
    {
        $collectionClass = 'App\Collection\\' . class_basename(static::class) . 'Collection';
        if (class_exists($collectionClass)) {
            return new $collectionClass($models);
        } else {
            return new BaseCollection($models);
        }
    }

    public function toApi()
    {
        return array_merge($this->attributesToArray(), $this->relationsToApi());
    }

    public function relationsToApi()
    {
        $attributes = [];

        foreach ($this->getArrayableRelations() as $key => $value) {
            // If the values implements the Arrayable interface we can just call this
            // toArray method on the instances which will convert both models and
            // collections to their proper array form and we'll set the values.
            if ($value instanceof Arrayable) {
                $relation = $value->toApi();
            }

            // If the value is null, we'll still go ahead and set it in this list of
            // attributes since null is used to represent empty relationships if
            // if it a has one or belongs to type relationships on the models.
            elseif (is_null($value)) {
                $relation = $value;
            }

            // If the relationships snake-casing is enabled, we will snake case this
            // key so that the relation attribute is snake cased in this returned
            // array to the developers, making this consistent with attributes.
            if (static::$snakeAttributes) {
                $key = Str::snake($key);
            }

            // If the relation value has been set, we will set it on this attributes
            // list for returning. If it was not arrayable or null, we'll not set
            // the value on the array because it is some type of invalid value.
            if (isset($relation) || is_null($value)) {
                $attributes[$key] = $relation;
            }

            unset($relation);
        }

        return $attributes;
    }

    public function save(array $options = [])
    {
        if ($re = parent::save($options)) {
            foreach ($this->savedEvents as $v) {
                event($v);
            }

            foreach ($this->savedJobs as $v) {
                dispatch($v);
            }
            $this->savedEvents = [];
            $this->savedJobs = [];
        }

        return $re;
    }

    /**
     * 恢复被修改的字段
     * @param array|null $attributes
     * @return $this
     * @author xiaoze <zeasly@live.com>
     */
    public function restoreAttributes(array $attributes = null)
    {
        if ($attributes === null) {
            $this->attributes = $this->original;
        } else {
            $this->attributes = array_merge($this->attributes, Arr::only($this->original, $attributes));
        }

        return $this;
    }

}
