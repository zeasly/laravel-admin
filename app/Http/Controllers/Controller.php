<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $request;

    public $user;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @author xiaoze <zeasly@live.com>
     * @return Model
     */
    public function user()
    {
        if (!$this->user) {
            $this->user = $this->guard()->user();
        }

        return $this->user;
    }

    /**
     * 获取当前登录id
     * @author xiaoze <zeasly@live.com>
     * @return int|mixed
     */
    public function userId()
    {
        return $this->user() ? $this->user()->id : 0;
    }

    /**
     * 获取guard
     * @author xiaoze <zeasly@live.com>
     * @return mixed
     */
    public function guard()
    {
        return Auth::guard();
    }

    /**
     * 返回一个统一的json
     * @param int $status
     * @param string $message
     * @param array $data
     * @author xiaoze <zeasly@live.com>
     * @return \Illuminate\Http\JsonResponse
     */
    public static function jsonResponse(int $status = 1, $message = '操作成功', array $data = [])
    {
        if (is_array($message)) {
            $data = $message;
            $message = '操作成功';
        }
        return response()->json(['status' => $status, 'message' => $message, 'data' => $data]);
    }

    public function checkRights($rights)
    {
        if ($this->user()->checkRights($rights) == false) {
            throw new AuthorizationException('没有操作权限');
        }
    }
}
