@extends('layouts.admin')
@section('title', '管理中心')
@section('content')
    <div class="col-lg-6">
        <div class="item_name">
            <b>分类管理</b><span id="msg_2"></span>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-laptop fa-fw"></i> 站点信息
            </div>
            <div class="panel-body">
                <p>文章：{{$articleCount}}</p>
                <p>评论：{{$commentCount}}</p>
                <p>PHP版本：{{$phpVersion}}</p>
                <p>MySQL版本：{{$mysqlVersion}}</p>
                <p>服务器环境：{{$serverSoftware}}</p>
                <p>GD图形处理库：{{$gdVersion}}</p>
                <p>服务器空间允许上传最大文件：{{$uploadMaxFilesize}}</p>
            </div>
        </div>
    </div>
@endsection