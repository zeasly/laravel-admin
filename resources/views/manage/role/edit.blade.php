@extends('manage.layout.master')

@section('body')
    <blockquote class="layui-elem-quote">服务单位管理</blockquote>

    <form class="layui-form layui-form-pane" action="" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>

            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required"
                       value="{{old('name', $role->name)}}"
                       placeholder="请输入" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">权限</label>
            <div class="layui-input-block">
                <table class="layui-table">
                    <colgroup>
                        <col width="100">
                        <col>
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th>权限组</th>
                        <th>权限
                            <input type="checkbox" name="rights[]" value="_all" lay-filter="select-all" title="全选">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rightsList as $group)
                        <tr>
                            <td>{{ $group['title'] }}</td>
                            <td>
                                @foreach($group['list'] as $rights => $title)
                                    <input type="checkbox" name="rights[]" @if($role->hasRights($rights))checked
                                           @endif value="{{$rights}}" title="{{ $title }}">
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-inline">
                {{csrf_field()}}
                <button class="layui-btn" type="button" lay-submit="" lay-filter="ajax_sub_return">保存</button>
            </div>
        </div>

    </form>
@stop

@section('js')
    @include('manage.layout.curd_js')
    <script>
        layui.form.on('checkbox(select-all)', function (data) {
            // console.log(data.elem.checked); //是否被选中，true或者false
            $('input[name="rights[]"]').prop('checked', data.elem.checked);
            layui.form.render('checkbox');
        });
    </script>
@stop
