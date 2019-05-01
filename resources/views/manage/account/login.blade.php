<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>登陆{{env('APP_NAME_STR','管理系统')}}</title>
    <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/top.css')}}"/>
    <link rel="icon" href="{{asset('admin/img/ico.png')}}">
</head>
<style type="text/css">
    body {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
</style>
<body class="landbg">

<div class="landcont">

    <div class="landlogo">
        <img src="{{asset('admin/img/ico.png')}}"/>
    </div>
    <p class="landtit" style="text-align: center;">欢迎使用{{env('APP_NAME_STR','管理系统')}}</p>

    <form action="" method="post">

        <ul class="landUl">
            <li class="name"><input type="text" name="username" value="{{old('username') ? old('username') : ''}}"
                                    placeholder="输入您的账号"/></li>
            <li class="password"><input type="password" name="password" value="" placeholder="输入您的密码"/></li>
        </ul>
        <p style="color: red;text-align: center;margin-bottom: 10px;">
            @if($errors->has('username'))<span>*{{$errors->first('username')}}</span>@endif
            @if($errors->has('password'))<span>*{{$errors->first('password')}}</span>@endif
        </p>
        {{ csrf_field() }}
        <a href="" class="landbtn">
            <input class="landbtns" type="submit" value="登录"/>
        </a>

    </form>
</div>

</body>
</html>
