<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Controller extends \App\Http\Controllers\Controller
{

    /**
     * 确认guard
     * @return StatefulGuard
     * @author 穆风杰<hcy.php@qq.com>
     */
    public function guard()
    {
        return Auth::guard('manager');
    }

    /**
     * 错误信息页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 穆风杰<hcy.php@qq.com>
     */
    public function error()
    {
        $time = Session::has('time') ? Session::get('time') : 3;
        $url = Session::has('url') ? Session::get('url') : 'work';
        $msg = Session::has('msg') ? Session::get('msg') : '抱歉！访问授权失效，请重试！';
        $data = [
            'time' => $time,
            'url'  => $url,
            'msg'  => $msg,
        ];
        return view('manage.public.error', $data);
    }
}
