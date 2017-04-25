<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>民升点评管理系统</title>
    <link href="//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/normalize.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/loginstyle.css')}}">
    <script src="{{asset('js/jquery-2.1.1.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/icheck.min.js')}}" type="text/javascript"></script>
    <!--[if IE]>
    <script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="login">
    <div class="title">
        <img src="{{asset('img/ERP.png')}}" alt="" height="60">
    </div>
    <form action="{{route('user.login')}}" method="post">
        {{ csrf_field() }}
        <div class="input">
            <div class="input-box">
                <label for="username"><i class="fa fa-user" aria-hidden="true"></i></label>
                <input type="text" name="name" placeholder="用户名" id="username">
            </div>
        </div>
        <div class="input">
            <div class="input-box">
                <label for="password"><i class="fa fa-key" aria-hidden="true"></i></label>
                <input type="password" name="password" placeholder="密码" id="password">
            </div>
        </div>
        <div class="input-form">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">记住用户名和密码！</label>
            <div class="clear"></div>
        </div>
        <div class="input-form">
            <input type="submit" class="submit" value="登录">
        </div>
    </form>
    <script>
        $('input').icheck({
            checkboxClass: 'checkbox',
            checkedClass: 'checked',
            tap: true,
            insert: '<i class="fa fa-check" aria-hidden="true"></i>'
        });
        $('.input .input-box input').focus(function () {
            $(this).parents('.input').addClass('active');
        });
        $('.input .input-box input').blur(function () {
            $('.input.active').removeClass('active');
        });
    </script>
    <div class="footer">
        <p>Copyright © 2017 . All rights reserved.</p>
        <p>浙ICP备15027517号-4</p>
    </div>
</div>


@include('myflash::top-message')

</body>
</html>