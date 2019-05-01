<?php
return [
    'manager' => [
        'guard'  => 'manager',
        'except' => [],
        'rights' => [
            [
                'title' => '管理员管理',
                'list'  => [
                    'manager'       => '列表',
                    'managerAdd'    => '添加',
                    'managerEdit'   => '编辑',
                    'managerDelete' => '删除',
                ],
            ],
            [
                'title' => '角色管理',
                'list'  => [
                    'role'       => '列表',
                    'roleAdd'    => '添加',
                    'roleEdit'   => '编辑',
                    'roleDelete' => '删除',
                ],
            ],


        ],
    ],

];
