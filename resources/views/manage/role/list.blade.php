@extends('manage.layout.master')

@section('body')
    <blockquote class="layui-elem-quote">权限组管理</blockquote>

    <!-- 工具集 -->
    <div class="my-btn-box">
    <span class="fl">
 <a class="layui-btn btn-add btn-default" href="{{route('roleAdd')}}"
    id="btn-add">
     <i class="layui-icon">&#xe608;</i>添加权限组</a>
        <a class="layui-btn btn-add btn-default" id="reload"><i class="layui-icon">&#xe669;</i></a>
    </span>
    </div>


    <table class="layui-table">
        <colgroup>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th class="hidden-xs">ID</th>
            <th>名称</th>
            <th>添加时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody id="idFrom">
        @foreach($list as $v)
            <tr>
                <td class="hidden-xs">{{$v->id}}</td>
                <td>{{$v->name }}</td>
                <td>{{$v->create_time ?? '未知'}}</td>
                <td>
                    @rights('roleEdit')
                    <a class="layui-btn layui-btn-sm layui-btn-normal"
                       href="{{route('roleEdit',['id' => $v->id])}}"
                       lay-event="edit">编辑</a>
                    @endrights
                    @rights('roleDelete')
                    <a class="layui-btn layui-btn-sm layui-btn-danger"
                       onclick="del('{{ route('roleDelete', ['id' => $v->id]) }}')" lay-event="del">删除</a>
                    @endrights
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @include('manage.layout.pager')

@stop
