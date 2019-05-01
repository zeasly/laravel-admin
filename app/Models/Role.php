<?php

namespace App\Models;

class Role extends Base
{
    protected $table = 'role';

    protected $casts = ['rights' => 'array'];

    public function manager()
    {
        return $this->hasMany(Manager::class, 'role_id');
    }

    public function allow($rights, $all = true)
    {
        if (!is_array($this->rights)) {
            return false;
        }

        if (is_string($rights)) {
            return $this->hasRights($rights);
        } elseif (is_array($rights)) {
            return $all ? $this->allowAll($rights) : $this->allowOne($rights);
        } else {
            return false;
        }
    }

    /**
     * 传进来的权限是否全部允许
     * @param $rights
     * @author xiaoze <zeasly@live.com>
     * @return bool
     */
    public function allowAll($rights)
    {
        if (!$this->rights) {
            return false;
        }
        return count(array_intersect($rights, $this->rights)) == count($rights);
    }

    /**
     * 传进来的权限是否有一个允许
     * @param $rights
     * @author xiaoze <zeasly@live.com>
     * @return bool
     */
    public function allowOne($rights)
    {
        if (!$this->rights) {
            return false;
        }
        return boolval(array_intersect($rights, $this->rights));
    }

    public function hasRights($rights)
    {
        if (!$this->rights) {
            return false;
        }
        return in_array($rights, $this->rights);
    }

    public function scopeContainsRights($q, $rights)
    {
        return $q->whereJsonContains('rights', $rights);
    }
}
