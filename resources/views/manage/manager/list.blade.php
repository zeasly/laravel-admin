@extends('manage.layout.master')

@section('body')

    <blockquote class="layui-elem-quote">管理员列表</blockquote>

    <!-- 工具集 -->
    <div class="my-btn-box">
        <div class="fl">
            <button class="layui-btn btn-add btn-default" type="button"><i class="layui-icon">&#xe669;</i></button>
            <a class="layui-btn btn-add btn-default" href="{{route('managerAdd')}}">
                <i class="layui-icon">&#xe608;</i>添加账号
            </a>
        </div>
        <div class="fr">
            <form class="layui-form layui-form-pane" action="" method="get">
                <div class="layui-form-label">搜索条件：</div>
                <div class="layui-input-inline">
                    <input type="text" name="keyword" value="{{request()->get('keyword')}}"
                           placeholder="管理员账户/姓名" class="layui-input"/>
                </div>
                <button type="submit" class="layui-btn mgl-20"><i class="layui-icon">&#xe615;</i> 查询</button>
            </form>
        </div>
    </div>


    <table class="layui-table">
        <thead>
        <tr>
            <th class="hidden-xs">管理员ID</th>
            <th>管理账户</th>
            <th class="hidden-xs">真实姓名</th>
            <th class="hidden-xs">用户角色</th>
            <th class="hidden-xs">账户状态</th>
            <th class="hidden-xs">创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody id="idFrom">

        @foreach($list as $key=>$vo)
            <tr>
                <td class="hidden-xs">{{$vo->id}}</td>
                <td>{{$vo->username}}</td>
                <td class="hidden-xs">{{$vo->name}}</td>
                <td class="hidden-xs">{{$vo->role->name}}</td>
                <td class="hidden-xs">{{$vo->statusText()}}</td>
                <td class="hidden-xs">{{$vo->create_time}}</td>
                <td>
                    <a class="layui-btn layui-btn-sm layui-btn-normal"
                       href="{{route('managerEdit',['id' => $vo->id])}}"
                       lay-event="edit">编辑/查看</a>
                    <a class="layui-btn layui-btn-sm layui-btn-danger"
                       onclick="del('{{ route('managerDelete', ['id' => $vo->id]) }}')" lay-event="del">删除</a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    @include('manage.layout.pager')
@stop

@section('js')
    @include('manage.layout.curd_js')
@stop