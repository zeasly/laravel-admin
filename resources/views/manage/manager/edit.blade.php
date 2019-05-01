@extends('manage.layout.master')

@section('body')
    <blockquote class="layui-elem-quote">账号管理</blockquote>

    <form class="layui-form layui-form-pane" action="" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>

            <div class="layui-input-block">
                <input type="text" name="username" value="{{old('username') ? old('username') : $manager->username}}"
                       lay-verify="required"
                       placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>

            <div class="layui-input-block">
                <input type="password" name="password" value=""
                       placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">姓名</label>

            <div class="layui-input-block">
                <input type="text" name="name" value="{{old('name') ? old('name') : $manager->name}}"
                       placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">联系方式</label>

            <div class="layui-input-block">
                <input type="text" name="mobile" value="{{$manager->mobile}}"
                       placeholder="请输入联系方式" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">用户角色</label>
            <div class="layui-input-inline">
                <select name="role_id">
                    @foreach($roleList as $v)
                        <option value="{{ $v->id }}"
                                @if($manager->role_id == $v->id) selected @endif>{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">帐户状态</label>
            <div class="layui-input-block">
                @foreach($statusList as $key=>$v)
                    <input type="radio" name="status" value="{{$key}}" @if($key == ($manager->status ?? 1)) checked
                           @endif title="{{$v}}">
                @endforeach
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="remark" placeholder="请输入备注内容" class="layui-textarea">{{$manager->remark}}</textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-inline">
                {{csrf_field()}}
                <button class="layui-btn" type="button" lay-submit="" lay-filter="ajax_sub_return">保 存</button>
            </div>
        </div>

    </form>
@stop

@section('js')
    @include('manage.layout.curd_js')
@stop