<?php

namespace App\Models;

use App\Collection\BaseCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

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
        static::addGlobalScope(
            'defaultSort',
            function (Builder $builder) {
                $builder->orderBy($builder->getModel()->table . '.' . $builder->getModel()->primaryKey, 'desc');
            }
        );
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
        return $this->attributesToArray();
    }

    public function save(array $options = [])
    {
        if ($re = parent::save($options)) {
            $events = $this->savedEvents;
            $this->savedEvents = [];
            while ($events) {
                event(array_shift($events));
            }

            $jobs = $this->savedJobs;
            $this->savedJobs = [];
            while ($jobs) {
                dispatch(array_shift($jobs));
            }
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
