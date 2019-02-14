<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title')</title>

    <!-- core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}">
    <link href="{{ asset('master/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('master/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('master/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('master/css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('master/css/main.css?ver=11') }}" rel="stylesheet">
    <link href="{{ asset('master/css/style.css?ver=11') }}" rel="stylesheet">
    <link href="{{ asset('master/css/responsive.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <!-- owl  -->
    <link rel="stylesheet" href="{{ asset('master/owl/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('master/owl/assets/owl.theme.default.min.css') }}">

    <!--[if lt IE 9]>
    <script src="{{ asset('master/js/html5shiv.js')}}"></script>
    <script src="{{ asset('master/js/respond.min.js') }}"></script>
    <![endif]-->
    <!-- <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png"> -->
    @yield('header-styles')
</head><!--/head-->

<body class="homepage">

    @include('layouts._header_master')

    @yield('content')

    @include('layouts._footer_master')

    <script src="{{ asset('master/js/jquery.js') }}"></script>
    <script src="{{ asset('master/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('master/js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('master/js/jquery.isotope.min.js') }}"></script>
    <script src="{{ asset('master/js/main.js?ver=3') }}"></script>
    @yield('footer-script')
</body>
</html>