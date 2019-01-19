<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @css('/bootstrap/css/bootstrap.min.css')
    @js('/js/jquery.min.js')
    @js('/bootstrap/js/bootstrap.min.js')
    <title>@yield('title')</title>
</head>
<body>
    @yield('content')
</body>
</html>