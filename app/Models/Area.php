<?php

namespace App\Models;

class Area extends Base
{
    protected $table = 'area';

    public function children()
    {
        return $this->hasMany(static::class, 'parentid');
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parentid');
    }

    public function province()
    {
        return $this->belongsTo(self::class, 'province_id');
    }

    /**
     * 获取树结构
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|static[]
     * @author 穆风杰<hcy.php@qq.com>
     */
    public static function getTree()
    {
        $list = static::query();
        $list->select('id', 'areaname as name', 'parentid');
        $list->province();
        //.children
        $list->load([
            'children' => function ($q) {
                $q->select('id', 'areaname as name', 'parentid');
                return $q->with([
                    'children' => function ($q) {
                        $q->select('id', 'areaname as name', 'parentid');
                        return $q->with([
                            'children' => function ($q) {
                                $q->select('id', 'areaname as name', 'parentid');
                            },
                        ]);
                    },
                ]);
            },
        ]);
        return $list;
    }

    //获取省
    public function scopeProvince($q)
    {
        $q->where('level', 1);
        return $this;
    }

    /**
     * 获取已开通城市树结构
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|static[]
     * @author 穆风杰<hcy.php@qq.com>
     */
    public static function getNewTree($provinceIds = [], $cityIds = [])
    {
        $list = static::query();
        $list->select('id', 'areaname as name', 'parentid');
        $list->province();
        $list->whereIn('id', $provinceIds);
        $list = $list->get();
        $list->load([
            'children' => function ($q) use ($cityIds) {
                $q->whereIn('id', $cityIds)->select('id', 'areaname as name', 'parentid');
                return $q->with([
                    'children' => function ($q) {
                        $q->select('id', 'areaname as name', 'parentid');
                        return $q->with([
                            'children' => function ($q) {
                                $q->select('id', 'areaname as name', 'parentid');
                            },
                        ]);
                    },
                ]);
            },
        ]);

        return $list;
    }

    public static function getByLocation($latitude, $longitude, $level = 2)
    {
        $builder = static::where('level', $level);
        $orderBy = 'ACOS(
        SIN((' . $latitude . ' * 3.1415) / 180 )
        *SIN((lat * 3.1415) / 180 )
        +COS((' . $latitude . ' * 3.1415) / 180 )
        * COS((lat * 3.1415) / 180 )
        *COS((' . $longitude . ' * 3.1415) / 180 - (lng * 3.1415) / 180 )
        ) * 6380  asc';

        $builder->orderByRaw($orderBy);

        return $builder->first();
    }

}
