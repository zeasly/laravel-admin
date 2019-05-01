<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Manager extends Base implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;

    /**
     * 定义数据库表名
     *
     * @var string
     */
    protected $table = 'manager';

    protected $guarded = ['id', 'password'];

    protected $hidden = ['password', 'remember_token'];


    public static $statusList = [
        1 => '有效',
        2 => '无效',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id')->withDefault(['name' => '暂无角色组']);
    }


    public static function getByUsername($username, $filterId = null)
    {
        return static::where('username', $username)
            ->when(
                $filterId,
                function ($q) use ($filterId) {
                    $q->where('id', '!=', $filterId);
                }
            )->first();
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);

        return $this;
    }

    public function checkPassword($pwd)
    {
        return Hash::check($pwd, $this->password);
    }

    public function can($ability, $arguments = [])
    {
        return $this->group->allow($ability);
    }

    public function statusText()
    {
        return static::$statusList[$this->status] ?? '未知';
    }

    public function setRole($role)
    {
        $this->role()->associate($role);
        return $this;
    }


    public function scopeKeyword($q, $keyword)
    {
        return $q->where(function ($q) use ($keyword) {
            $q->where('username', 'like', "%$keyword%")
                ->orWhere('name', 'like', "%$keyword%");
        });
    }


    public function checkRights($ability, $all = true)
    {
        if ($this->isSuper()) {
            return true;
        }
        return $this->role->allow($ability, $all);
    }

    /**
     * 判断是不是超管
     * @return bool
     * @author xiaoze <zeasly@live.com>
     */
    public function isSuper()
    {
        return $this->id == 1;
    }

}
