@extends('layouts.admin')
@section('title', '分类管理')
@section('content')
        <div class="panel-heading">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="/admin/setting">基本设置</a></li>
                <li role="presentation"><a href="/admin/personal">个人设置</a></li>
            </ul>
        </div>
        <div class="panel-body">
            <form action="/admin/setting" method="post">
                <div class="form-group">
                    <label>站点标题：</label><input class="form-control" value="{{$setting->site_title}}" name="site_title">
                </div>
                <div class="form-group">
                    <label>站点关键字：</label><input class="form-control" value="{{$setting->site_keywords}}" name="site_keywords">
                </div>
                <div class="form-group">
                    <label>站点描述：</label><input class="form-control" value="{{$setting->site_description}}" name="site_description">
                </div>
                {{--<div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="login_verify_code" @if($setting->login_verify_code === 1) checked @endif>登录验证码
                        </label>
                    </div>
                </div>--}}
                <div class="form-group form-inline">
                    <label>每页显示</label> <input class="form-control" style="width:50px" value="{{$setting->article_limit}}" name="article_limit"> 篇文章
                </div>
                {{--<div class="form-group">
                    <div class="checkbox form-inline">
                        <label><input type="checkbox" name="allow_comment" @if($setting->allow_comment === 1) checked @endif>开启评论</label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="comment_verify_code" @if($setting->comment_verify_code === 1) checked @endif>评论验证码
                        </label>
                    </div>
                </div>--}}
                <div class="form-group form-inline">
                    <label>每页显示</label> <input maxlength="5" style="width:50px;" class="form-control" value="{{$setting->comment_limit}}" name="comment_limit"> 条评论
                </div>
                <div class="form-group">
                    ICP备案号：
                    <input maxlength="200" class="form-control" value="{{$setting->icp_no}}" name="icp_no">
                </div>
                <input type="submit" value="保存设置" class="btn btn-primary">
            </form>
        </div>
@endsection