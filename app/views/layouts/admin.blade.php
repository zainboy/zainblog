<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - 管理后台</title>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <script type="text/javascript" charset="utf-8" src="/js/jquery.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/ueditor/zh-cn/zh-cn.js"></script>
    <style>
        body {
            font-family:"Microsoft Yahei";
            background: #f8f8f8;
        }
        .admin {
            width:100%;
        }
        .admin #main_content {
            background-color: #fff;
            padding:25px;
        }

        .navbar-top-links li {
            display: inline-block;
        }

        .navbar-top-links li:last-child {
            margin-right: 15px;
        }

        .navbar-top-links li a {
            padding: 15px;
            min-height: 50px;
        }

        .navbar-top-links .dropdown-menu li {
            display: block;
        }

        .navbar-top-links .dropdown-menu li:last-child {
            margin-right: 0;
        }

        .navbar-top-links .dropdown-menu li a {
            padding: 3px 20px;
            min-height: 0;
        }

        .navbar-top-links .dropdown-menu li a div {
            white-space: normal;
        }

        .sidebar .sidebar-nav.navbar-collapse {
            padding-right: 0;
            padding-left: 0;
        }

        .sidebar .sidebar-avatar img {
            border: 1px solid #ccc;
            margin:10px 0;
            width: 70px;
        }

        .sidebar ul li {
            border-bottom: 1px solid #e7e7e7;
        }

        .sidebar ul li a.active {
            background-color: #eee;
        }


        .sidebar .nav-second-level li,
        .sidebar .nav-third-level li {
            border-bottom: 0!important;
        }

        .sidebar .nav-second-level li a {
            padding-left: 37px;
        }

        .sidebar .nav-third-level li a {
            padding-left: 52px;
        }

        .admin #main_content .item_name {
            font-size: 16px;
            color: #666666;
            margin-bottom: 15px;
        }

        .admin #main_content .title {
            margin:20px 0;
        }
        #content_old {
            display: none;
        }
        .admin .care {
            color:#C13932;
        }
        .admin .filters {
            margin:20px auto;
        }
        
        @media(min-width:768px) {
            .sidebar {
                z-index: 1;
                position: absolute;
                width: 250px;
                margin-top: 51px;
            }
            .navbar-top-links.navbar-right{
                margin-right:0;
            }
            .admin #main_content {
                position: inherit;
                margin: 0 0 0 250px;
                border-left: 1px solid #e7e7e7;
            }
        }
        @media(max-width: 1200px) {
            .admin #main_content {
                padding:15px;
            }
        }

    </style>
</head>
<body>
    <div class="admin">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/" target="_blank" title="在新窗口浏站点">
                    {{$admin->nickname}}的博客
                </a>
            </div>
            <ul class="nav navbar-top-links navbar-right">
                <li><a href="/admin"><i class="glyphicon glyphicon-home fa-fw"></i>管理首页</a></li>
                <li><a href="/admin/setting"><i class="glyphicon glyphicon-wrench fa-fw"></i> 设置</a></li>
                <li><a href="/logout"><i class="glyphicon glyphicon-power-off fa-fw"></i>退出</a></li>
            </ul>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-avatar text-center">
                            <a href="/admin/personal">
                                <img class="img-circle" src="{{$admin->avatar or '/img/avatar.jpg'}}" alt="">
                            </a>
                        </li>
                        <li><a class="@if($segment[1] == 'admin' && $segment[2] == 'write') active @endif" href="/admin/write" id="menu_wt"><i class="glyphicon glyphicon-edit fa-fw"></i> 撰写文章</a></li>
                        <li><a class="@if($segment[1] == 'admin' && $segment[2] == 'posts') active @endif" href="/admin/articles" id="menu_log"><i class="glyphicon glyphicon-list-alt fa-fw"></i> 文章</a></li>
                        <li><a class="@if($segment[1] == 'admin' && $segment[2] == 'sort') active @endif" href="/admin/sort" id="menu_sort"><i class="glyphicon glyphicon-flag fa-fw"></i> 分类</a></li>
                        <li><a class="@if($segment[1] == 'admin' && $segment[2] == 'comment') active @endif" href="/admin/comment" id="menu_cm"><i class="glyphicon glyphicon-comment fa-fw"></i> 评论
                            </a></li>
                        {{--<li><a class="@if($segment[1] == 'admin' && $segment[2] == 'link') active @endif" href="/admin/link" id="menu_link"><i class="glyphicon glyphicon-link fa-fw"></i> 友链</a></li>--}}
                        <li><a class="@if($segment[1] == 'admin' && $segment[2] == 'setting') active @endif" href="/admin/setting" id="menu_link"><i class="glyphicon glyphicon-wrench fa-fw"></i> 基本设置</a></li>
                        <li><a class="@if($segment[1] == 'admin' && $segment[2] == 'personal') active @endif" href="/admin/personal" id="menu_link"><i class="glyphicon glyphicon-user fa-fw"></i> 个人设置</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="main_content">
        @yield('content')
        </div>
    </div>
    <script>
        $(window).bind('load resize',function(){
            topOffset = 51;
            width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
            if (width < 768) {
                $('div.navbar-collapse').addClass('collapse')
                topOffset = 101; // 2-row-menu
            } else {
                $('div.navbar-collapse').removeClass('collapse')
            }

            height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
            height = height - topOffset;
            if (height < 1) height = 1;
            if (height > topOffset) {
                $("#main_content").css("min-height", (height) + "px");
            }
        });
    </script>
</body>
</html>