<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('auth.layouts.head-tag')
    @yield('head-tag')
</head>
<body dir="rtl">
    @yield('content')
</body>
    @yield('script')
</html>
