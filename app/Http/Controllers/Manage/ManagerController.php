<?php

namespace App\Http\Controllers\Manage;

use App\Models\Manager;
use App\Models\Role;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function lists(Request $request)
    {
        /** @var Manager $builder */
        $builder = Manager::query();
        if ($_ = $request->get('keyword')) {
            $builder->keyword($_);
        }
        $list = $builder->paginate();
        $list->load('role');
        $data = [
            'list' => $list,
        ];
        return view('manage.manager.list', $data);
    }

    public function add(Request $request)
    {
        $this->checkRights('managerAdd');
        if ($request->isMethod('POST')) {
            $data = $this->validate(
                $request,
                [
                    'username' => 'required|unique:manager,username',
                    'password' => 'required',
                    'name'     => '',
                    'role_id'  => 'required',
                    'status'   => 'required',
                    'mobile'   => '',
                    'remark'   => '',
                ]
            );
            $manager = new Manager($data);
            $manager->password = $data['password'];
            $manager->save();

            return $this->jsonResponse(1, '管理员添加成功');
        }

        $data = [
            'manager'    => new Manager(),
            'roleList'   => Role::all(),
            'statusList' => Manager::$statusList,
        ];
        return view('manage.manager.edit', $data);
    }

    public function edit(Request $request, $id)
    {
        $manager = Manager::initById($id);
        if ($request->isMethod('POST')) {
            $data = $this->validate(
                $request,
                [
                    'username' => 'required|unique:manager,username,' . $id,
                    'password' => '',
                    'name'     => '',
                    'role_id'  => 'required',
                    'status'   => 'required',
                    'mobile'   => '',
                    'remark'   => '',
                ]
            );
            if ($data['password']) {
                $manager->password = $data['password'];
            }
            $manager->fill($data);
            $manager->save();

            return $this->jsonResponse(1, '编辑成功');
        }
        $data = [
            'manager'    => $manager,
            'roleList'   => Role::all(),
            'statusList' => Manager::$statusList,
        ];

        return view('manage.manager.edit', $data);
    }


    public function delete($id)
    {
        $Manager = Manager::initById($id);
        $Manager->delete();
        return $this->jsonResponse(1, '删除成功');
    }
}
