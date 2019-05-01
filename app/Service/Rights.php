<?php

namespace App\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Route;

/**
 * Class Base
 *
 * @package App\Service
 */
class Rights
{
    public static $instance = [];

    public $rights = [];

    public $allRights = [];

    public $except = [];

    public $guard;

    protected function __construct()
    {
        // do nothing
    }

    public static function config($config = null)
    {
        if ($config) {
            return config('rights.' . $config, []);
        } else {
            return config('rights', []);
        }
    }

    /**
     * @param null $config
     * @return Rights|bool
     * @author xiaoze <zeasly@live.com>
     */
    public static function instance($config = null)
    {
        if (!$config) {
            $config = static::getDefaultConfig();
        }

        if (isset(static::$instance[$config])) {
            return isset(static::$instance[$config]);
        }

        $new = new static();
        return $new->init($config);
    }

    public static function getDefaultConfig()
    {
        return key(static::config());
    }

    /**
     * 初始化
     * @param null $config
     * @return $this
     * @throws \Exception
     * @author xiaoze <zeasly@live.com>
     */
    public function init($config = null)
    {
        if ($config) {
            $c = static::config($config);
        } else {
            $c = static::config();
            $c = reset($c);
        }
        if (!$c) {
            throw new \Exception('权限配置不存在', 500);
        }

        $this->guard = array_get($c, 'guard');
        $this->rights = array_get($c, 'rights');
        $this->except = array_get($c, 'except');

        return $this;
    }

    /**
     * 判断是否有权限
     * @param $rights
     * @return bool
     * @author xiaoze <zeasly@live.com>
     */
    public function check($rights)
    {
        if (in_array($rights, $this->except)) {
            return true;
        }

        if ($this->checkUserRights($rights)) {
            return true;
        }

        if ($this->hasRights($rights)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查用户有没有权限
     * @param $rights
     * @param bool $all
     * @return mixed
     * @author xiaoze <zeasly@live.com>
     */
    public function checkUserRights($rights)
    {
        if (!$this->guard()->user()) {
            return false;
        }
        return $this->guard()->user()->checkRights($rights);
    }

    /**
     * 获取guard
     * @return SessionGuard
     * @author xiaoze <zeasly@live.com>
     */
    public function guard()
    {
        return Auth::guard($this->guard);
    }

    public function getAllRights()
    {
        if (is_null($this->allRights)) {
            $list = [];
            foreach ($this->rights as $v) {
                $list = array_merge($list, $v['list']);
            }
            $this->allRights = $list;
        }

        return $this->allRights;
    }

    public function hasRights($rights)
    {
        foreach ($this->rights as $v) {
            if (isset($v['list'][$rights])) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取当前控制器名
     *
     * @return string
     */
    public static function getCurrentControllerName()
    {
        return static::getCurrentAction()['controller'];
    }

    /**
     * 获取当前方法名
     *
     * @return string
     */
    public static function getCurrentMethodName()
    {
        return static::getCurrentAction()['method'];
    }

    /**
     * 获取当前控制器与方法
     *
     * @return array
     */
    public static function getCurrentAction()
    {
        $action = Route::current()->getActionName();
        list($class, $method) = explode('@', $action);

        return ['controller' => $class, 'method' => $method];
    }
}
