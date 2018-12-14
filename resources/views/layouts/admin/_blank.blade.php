<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title')
    </title>

    @yield('header-styles')

</head>

<body>

    @yield('content')

    @yield('footer-script')

</body>

</html>