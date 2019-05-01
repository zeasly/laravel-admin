@extends('manage.layout.master')

@section('body')
    <blockquote class="layui-elem-quote">修改密码</blockquote>
    <form class="layui-form layui-form-pane" action="" method="post">

        <div class="layui-form-item">
            <label class="layui-form-label">旧密码</label>
            <div class="layui-input-inline">
                <input type="password" name="passwordOld" value="" lay-verify="required"
                       placeholder="请输入旧密码" autocomplete="off" class="layui-input">
            </div>
            @if($errors->first('passwordOld'))
                <div class="layui-form-mid red">*{{$errors->first('passwordOld')}}</div> @endif
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">新密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password" value="" lay-verify="required"
                       placeholder="请输入新密码" autocomplete="off" class="layui-input">
            </div>
            @if($errors->first('password'))
                <div class="layui-form-mid red">*{{$errors->first('password')}}</div> @endif
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password_confirmation" value="" lay-verify="required"
                       placeholder="再次确认密码" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-inline">
                {{csrf_field()}}
                <button class="layui-btn" lay-submit="" lay-filter="ajax_sub">修 改</button>
                <button class="layui-btn layui-btn-normal" type="button" onclick="closeSelf()">取消</button>
            </div>
        </div>
    </form>
@stop