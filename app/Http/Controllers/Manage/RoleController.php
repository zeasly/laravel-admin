<?php

namespace App\Http\Controllers\Manage;

use App\Models\Role;
use App\Service\Rights;

class RoleController extends Controller
{
    public function lists()
    {
        $list = Role::query();
        $list = $list->paginate();
        $data = [
            'list' => $list,
        ];
        return view('manage.role.list', $data);
    }

    public function add()
    {
        if ($this->request->isMethod('post')) {
            $data = $this->validate(
                $this->request,
                [
                    'name'   => 'required',
                    'rights' => 'array',
                ],
                [
                    'name.required' => '角色名必须填写',
                    'rights'        => '权限数据有误',
                ]
            );
            if (isset($data['rights'])) {
                $data['rights'] = array_values($data['rights']);
            }
            $role = new Role($data);
            $role->save();
            return $this->jsonResponse(1, '添加成功');
        }
        $data = [
            'role'       => new Role(),
            'rightsList' => Rights::config('manager')['rights'],
        ];
        return view('manage.role.edit', $data);
    }

    public function edit($id)
    {
        $role = Role::initById($id);
        if ($this->request->isMethod('post')) {
            $data = $this->validate(
                $this->request,
                [
                    'name'   => 'required',
                    'rights' => 'array',
                ],
                [
                    'name.required' => '角色名必须填写',
                    'rights'        => '权限数据有误',
                ]
            );
            if (isset($data['rights'])) {
                $data['rights'] = array_values($data['rights']);
            }

            $role->fill($data);
            $role->save();
            return $this->jsonResponse(1, '编辑成功');
        }
        $data = [
            'role'       => $role,
            'rightsList' => Rights::config('manager')['rights'],
        ];
        return view('manage.role.edit', $data);
    }

    public function delete($id)
    {
        $role = Role::initById($id);
        $role->delete();

        return static::jsonResponse();
    }
}
