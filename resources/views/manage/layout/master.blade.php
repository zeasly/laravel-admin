<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@section('title')首页@show</title>
    <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
    <link rel="stylesheet" href="{{asset('admin/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('admin/css/extend.css')}}">
    <link rel="icon" href="{{asset('admin/img/ico.png')}}">

    <script type="text/javascript" src="{{asset('js/jquery-2.0.3.min.js')}}"></script>

    @yield('head')
    @section('style')
        <style>
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none !important;
            }
        </style>
    @show
</head>
<body class="body">
@yield('body')

</body>

<script type="text/javascript" src="{{asset('layui/layui.all.js')}}"></script>
<script type="text/javascript" src="{{asset('admin/js/vip_comm.js')}}"></script>
<script type="text/javascript" src="{{asset('js/function.js')}}"></script>

<script>
    if ('{{$errors->has('success')}}') {
        layer.msg('{{$errors->first('success')}}', {icon: 1, time: 2000});
    }

    if ('{{$errors->has('error')}}') {
        layer.msg('{{$errors->first('error')}}', {icon: 0, time: 2000});
    }


</script>
@yield('js')
</html>
