@extends('layouts.admin')
@section('title', '个人设置')
@section('content')
        <div class="panel-heading">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><a href="/admin/setting">基本设置</a></li>
                <li role="presentation" class="active"><a href="/admin/personal">个人设置</a></li>
            </ul>
        </div>
        <div class="panel-body">
            <form action="/admin/personal" method="post" enctype="multipart/form-data" onsubmit="return checkSubmit()">
                <div class="form-group">
                    <img src="{{$user->avatar or '/img/avatar.jpg'}}" style="width:80px;height:80px;" alt="">
                </div>
                <div class="form-group">
                    <label for="avatar">头像(支持JPG、PNG格式图片)</label>
                    <input type="file" id="avatar" name="avatar">
                </div>
                <div class="form-group">
                    <label for="username">登录名：</label><input class="form-control" value="{{$user->username}}" id="username" name="username">
                </div>
                <div class="form-group">
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <label for="nickname">昵称：</label><input class="form-control" value="{{$user->nickname}}" id="nickname" name="nickname">
                </div>
                <div class="form-group">
                    <label for="email">邮箱：</label><input class="form-control" type="email" value="{{$user->email}}" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="description">个人描述：</label><textarea placeholder="The guy was lazy and didn't leave anything" id="description" name="description" cols="" rows="3" class="form-control">{{$user->description}}</textarea>
                </div>
                <div class="form-group">
                    <label for="password">新密码：</label><input class="form-control" type="password" value="" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="repeat_password">再次新密码：</label><input class="form-control" type="password" value="" id="repeat_password" name="repeat_password">
                </div>
                <input type="submit" value="保存设置" class="btn btn-primary">
            </form>
        </div>
        <script>
            function checkSubmit() {
                var username = $('#username').val();
                var nickname = $('#nickname').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var repeat_password = $('#repeat_password').val();
                if(!username) {
                    $('#username').focus();
                    return false;
                }
                if(!nickname) {
                    $('#nickname').focus();
                    return false;
                }
                if(!email) {
                    $('#email').focus();
                    return false;
                }
                if(!password) {
                    $('#password').focus();
                    return false;
                }
                if(!repeat_password) {
                    $('#repeat_password').focus();
                    return false;
                }
                if(password !== repeat_password) {
                    alert('两次密码不一致');
                    return false;
                }
                return true;
            }
        </script>
@endsection