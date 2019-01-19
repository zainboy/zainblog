@extends('layouts.bootstrap')
@section('title', 'Page not found')
@section('content')
<style>
    body {
        background: #f3f3f3;
    }
    .error_page {
        position: absolute;
        height: 220px;
        width: 300px;
        left: 50%;
        top: 50%;
        margin-left: -150px;
        margin-top: -110px;
        text-align: center;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
</style>
<div class="error_page">
        <img src="/img/404.png" alt="404">
        <h4>Sorry, 页面没找到.</h4>
        <a href="/" class="btn btn-warning">返回首页</a>
</div>
@endsection