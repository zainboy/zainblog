<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/zain.css">
    <script src="/js/jquery.min.js"></script>
    <title>@yield('title') - zain的博客</title>
</head>
<body>
@include('header')
<div class="container main">
    <div class="row">
        <div class="col-md-9">
            @yield('content')
        </div>
        <div class="col-md-3">
            @include('sidebar')
        </div>
    </div>
</div>
@include('footer')
</body>
</html>