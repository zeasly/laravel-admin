<?php

namespace App\Http\Controllers\Manage;

use App\Models\Manager;
use App\Models\Role;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/manage';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('guest:manager')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function credentials(Request $request)
    {
        $re = $request->only($this->username(), 'password');
        $re['status'] = 1;

        return $re;
    }

    //登陆数据
    public function showLoginForm()
    {
        if (request()->isMethod('post')) {
            return $this->login(request());
        }
        return view('manage.account.login');
    }

    public function siteLogin()
    {
        if ($this->request->isMethod('post')) {
            $manager = Manager::getByUsername($this->request->get('username'));
            if ($manager == null) {
                //检查用户组是否存在
                $role = Role::getSiteRole();
                if ($role == null) {
                    return self::jsonResponse(-1, '还没有设置[坐席人员]用户角色,请联系管理员');
                }
                //没有就新建一个用户
                $manager = new Manager();
                $manager->setRoleAttribute($role);
                $manager->username = $this->request->get('username');
                $manager->name = '坐席客服:' . $this->request->get('username');
                $manager->save();
            }

            $this->guard()->login($manager);

            return self::jsonResponse();
        }

        return view('manager.account.site_login');
    }

    //退出登陆
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect()->route('manage');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect($this->redirectTo());
    }

    public function guard()
    {
        return parent::guard();
    }

    public function redirectTo()
    {
        return route('manage');
    }

}
