@extends('manage.layout.master')

@section('body')
    <blockquote class="layui-elem-quote">新增管理</blockquote>

    <form class="layui-form layui-form-pane" action="" method="post">

        <div class="layui-form-item">
            <label class="layui-form-label">真实名</label>

            <div class="layui-input-block">
                <input type="text" name="name" value="{{old('name')}}"
                       placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">登陆名</label>

            <div class="layui-input-block">
                <input type="text" name="username" value="{{old('username')}}" lay-verify="required"
                       placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
            @if($errors->first('username'))
                <div class="layui-form-mid red">*{{$errors->first('username')}}</div> @endif
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">登陆密码</label>

            <div class="layui-input-block">
                <input type="password" name="password" value="{{old('password')}}" lay-verify="required"
                       placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">联系方式</label>

            <div class="layui-input-block">
                <input type="text" name="mobile" value="{{old('mobile')}}"
                       placeholder="请输入联系方式" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">用户角色</label>

            <div class="layui-input-block">
                <select name="role_id">
                    @foreach($roleList as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">帐户状态</label>
            <div class="layui-input-block">
                @foreach(\App\Models\Manager::$statusList as $key=>$vo)
                    <input type="radio" name="status" value="{{$key}}" @if($key == 1) checked @endif title="{{$vo}}">
                @endforeach
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="remark" placeholder="请输入备注内容" class="layui-textarea"></textarea>
            </div>
        </div>


        <div class="layui-form-item">
            <div class="layui-input-inline">
                {{csrf_field()}}
                <button class="layui-btn" type="button" lay-submit="" lay-filter="ajax_sub">添 加</button>
                <button class="layui-btn layui-btn-normal" type="button" onclick="closeSelf()">取消</button>
            </div>
        </div>

    </form>
@stop
