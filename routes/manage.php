<?php

//登陆
Route::any('login', 'AccountController@showLoginForm')->name('login');

//后台其它操作流程路由
Route::group(
    ['middleware' => 'auth:manager'],
    function () {

        Route::get('', 'IndexController@index')->name('manage');
        Route::get('logout', 'AccountController@logout')->name('logout');

        //管理员
        Route::group(
            ['prefix' => 'manager'],
            function () {
                Route::any('', 'ManagerController@lists')->name('manager');
                Route::any('add', 'ManagerController@add')->name('managerAdd');
                Route::any('{id}/eit', 'ManagerController@edit')->name('managerEdit');
                Route::any('{id}', 'ManagerController@delete')->name('managerDelete');
            }
        );

        Route::prefix('role')
            ->group(function () {
                Route::get('', 'RoleController@lists')->name('role');
                Route::any('add', 'RoleController@add')->name('roleAdd');
                Route::any('{id}/edit', 'RoleController@edit')->name('roleEdit');
                Route::delete('{id}', 'RoleController@delete')->name('roleDelete');
            });

    }
);
