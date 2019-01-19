@extends('layouts.bootstrap')
@section('title', '登录')
@section('content')
    <style type="text/css">
        html {
            font-size: 100%;
        }
        .admin-login {
            position: absolute;
            top:50%;
            left:50%;
            height:174px;
            width:300px;
            margin-left:-150px;
            margin-top:-87px;
        }
        .admin-login a {
            margin-left:10px;
        }
    </style>
    <div class="container admin-login">
            <form class="form-horizontal" action="/login" role="form" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="用户名">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="密码">
                </div>
                {{--<div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input name="remember" type="checkbox"> 记住密码
                        </label>
                    </div>
                </div>--}}
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">登录后台</button>
                    <a href="/" class="btn btn-default" role="button">返回首页</a>
                </div>
            </form>
    </div>
@endsection