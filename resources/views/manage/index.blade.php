@extends('manage.layout.master')

@section('style')
    <style>
        .my-header-user-nav li {
            margin-right: 20px;
        }
    </style>
@stop

@section('body')
    <!-- layout admin -->
    <div class="layui-layout layui-layout-admin"> <!-- 添加skin-1类可手动修改主题为纯白，添加skin-2类可手动修改主题为蓝白 -->
        <!-- header -->
        <div class="layui-header my-header">
            <a href="{{route('manage')}}">
                <div class="my-header-logo">{{env('APP_NAME_STR','后台管理系统')}}</div>
            </a>
            <div class="my-header-btn">
                <button class="layui-btn layui-btn-sm btn-nav"><i class="layui-icon">&#xe65f;</i></button>
            </div>

            <!-- 顶部右侧添加选项卡监听 -->
            <ul class="layui-nav my-header-user-nav" lay-filter="side-top-right">
                <li class="layui-nav-item">
                    <a class="name" href="javascript:;"><img src="{{asset('admin/img/ico.png')}}"
                                                             alt="logo"> {{request()->user('manager')->username}}  </a>
                    <dl class="layui-nav-child" style="cursor: pointer;">
                        <dd>
                            <a onclick="openView('','修改密码','350px','400px')"><i class="layui-icon">&#xe620;</i>修改密码</a>
                        </dd>

                        <dd>
                            <a onclick="logout()"><i class="layui-icon">&#x1006;</i>退出</a>
                        </dd>
                    </dl>
                </li>
            </ul>

        </div>


        <!-- side -->
        <div class="layui-side my-side">
            <div class="layui-side-scroll">
                <!-- 左侧主菜单添加选项卡监听 -->
                <ul class="layui-nav layui-nav-tree" lay-filter="side-main">
                    @rights(['role', 'manager'], 'manager')
                    <li class="layui-nav-item">
                        <a href="javascript:;"><i class="layui-icon">&#xe613;</i>账号管理</a>
                        <dl class="layui-nav-child">
                            @rights('role', 'manager')
                            <dd><a href="javascript:;" href-url="{{route('role')}}"><i class="layui-icon">&#xe621;</i>角色列表</a>
                            </dd>
                            @endrights
                            @rights('manager', 'manager')
                            <dd><a href="javascript:;" href-url="{{route('manager')}}"><i
                                            class="layui-icon">&#xe621;</i>账号信息</a>
                            </dd>
                            @endrights
                        </dl>
                    </li>
                    @endrights
                </ul>
            </div>
        </div>


        <!-- body -->
        <div class="layui-body my-body">
            <div class="layui-tab layui-tab-card my-tab" lay-filter="card" lay-allowClose="true">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="1"><span><i class="layui-icon">&#xe629;</i>工作台</span></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <iframe id="iframe" src="" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer -->
        <div class="layui-footer my-footer">
            <p>
                <a href="javaScript:void(0)" target="_blank">{{env('APP_NAME_STR','管理系统')}}</a>
            </p>
        </div>
    </div>

    <!-- 右键菜单 -->
    <div class="my-dblclick-box none">
        <table class="layui-tab dblclick-tab">
            <tr class="card-refresh">
                <td><i class="layui-icon">&#xe669;</i>刷新当前标签</td>
            </tr>
            <tr class="card-close">
                <td><i class="layui-icon">&#x1006;</i>关闭当前标签</td>
            </tr>
            <tr class="card-close-all">
                <td><i class="layui-icon">&#x1006;</i>关闭所有标签</td>
            </tr>
        </table>
    </div>
@stop

@section('js')
    <script>
        // 工具
        function _util() {
            var bar = $('.layui-fixbar');
            // 分辨率小于1023  使用内部工具组件
            if ($(window).width() < 992) {
                layui.util.fixbar({
                    bar1: '&#xe602;'
                    , css: {left: 10, bottom: 54}
                    , click: function (type) {
                        if (type === 'bar1') {
                            //iframe层
                            layer.open({
                                type: 1,                        // 类型
                                title: false,                   // 标题
                                offset: 'l',                    // 定位 左边
                                closeBtn: 0,                    // 关闭按钮
                                anim: 0,                        // 动画
                                shadeClose: true,               // 点击遮罩关闭
                                shade: 0.8,                     // 半透明
                                area: ['150px', '100%'],        // 区域
                                skin: 'my-mobile',              // 样式
                                content: $('body .my-side').html() // 内容
                            });
                        }
                        layui.element.init();
                    }
                });
                bar.removeClass('layui-hide');
                bar.addClass('layui-show');
            } else {
                bar.removeClass('layui-show');
                bar.addClass('layui-hide');
            }
        }

        function logout() {
            layer.confirm(
                '您确认退出' + '{{env('APP_NAME_STR','后台管理系统')}}？',
                {
                    btnAlign: 'c',
                    btn: ['退出', '取消'],
                    title: '提示',
                    icon: 3,
                    closeBtn: 0
                }, function () {
                    window.location.href = '{{ route('logout') }}';
                }
            )
        }
    </script>
@stop

