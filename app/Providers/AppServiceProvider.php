<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //添加手机号验证
        Validator::extend(
            'mobile',
            function ($attribute, $value, $parameters) {
                return preg_match(
                    '/^1\\d{10}$/',
                    $value
                ) ? true : false;
            },
            '请输入正确的手机号'
        );

        //添加身份证验证规则
        Validator::extend(
            'identity',
            function ($attribute, $value, $parameters) {
                return preg_match(
                    '/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/',
                    $value
                );
            },
            '请输入正确的身份证号码'
        );


        //添加年龄验证规则
        Validator::extend(
            'age',
            function ($attribute, $value, $parameters) {
                return $value >= 0 && $value <= 150;
            },
            '请输入正确的年龄'
        );

        if (env('LOG_DB', false)) {
            DB::listen(
                function ($query) {
                    Log::channel('db')->debug("[{$query->time}ms]" . $query->sql, $query->bindings);
                }
            );
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
