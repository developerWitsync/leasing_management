<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/stylesheet.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/owlcarousel/owl.carousel.css') }}" rel="stylesheet">
    @yield('header-styles')
    <link rel="shortcut icon" href="{{ asset('master/images/favicon.png') }}">
    <style>
        .error_raiase_ticket {
            display: block;
            margin-top: 5px;
            margin-bottom: 5px;
            color: #e91717;
            font-style: italic;
        }
    </style>
</head>
<body oncontextmenu="return false" onkeydown="return false;" onmousedown="return false;">
<div>
    @if(request()->segment('1') != 'login' && request()->segment('1') != 'register')
        <nav class="navbar navbar-default navbar-static-top">
            <div class="hdrTop">

                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand logoBx" href="{{ url('/') }}">
                        <img src="{{ asset('assets/images/logo2.png') }}"/>
                    </a>
                    @if(env('BETA'))
                        <span style="cursor:pointer;" class="badge badge-success beta_version">Beta Version</span>
                    @endif
                </div>


                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                        <!--   <li><a href="{{ route('register') }}">Register</a></li>-->
                            <li><a href="{{ route('contactus') }}">Contact us</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->authorised_person_name }}
                                    | {{ getParentDetails()->legal_entity_name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    @if(auth()->user()->parent_id == '0')
                                        <li><a href="{{route('settings.profile.index')}}">My Profile</a></li>
                                    @endif
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>

            </div>
        </nav>
    @endif

    @if(auth()->check())
        <div class="dashOuter clearfix">
            <div class="dashLeft">

                <div class="leftNav">
                    @include('layouts._sidebar')
                </div>
                <div class="leftmenuHd">
                    <div class="menuHd">
                        <a class="dash_navicon" href="javascript:void(0)"
                           style="text-align: center;background-color: #CCCCCC">
                            <i class="fa fa-angle-right" aria-hidden="true" style="font-weight: bolder"></i>
                            {{--<span><i class="fa fa-angle-double-left" aria-hidden="true"></i>Move sidebar</span></a>--}}
                            <span><i class="fa fa-angle-left" aria-hidden="true" style="font-weight: bolder"></i></span></a>
                    </div>
                </div>
            </div>
            <div class="DashRight @if(request()->segment(1) == 'lease-valuation') backValuation @endif">
                <div class="rightContainer">
                    {{--@if(isset($breadcrumbs ))--}}
                    {{--<nav aria-label="breadcrumb">--}}
                    {{--<ol class="breadcrumb">--}}
                    {{--<li class="breadcrumb-item"><a href="/home">Dashboard</a></li>--}}
                    {{--@foreach($breadcrumbs as $breadcrumb)--}}
                    {{--<li class="breadcrumb-item"><a href="{{ $breadcrumb['link'] }}">{{ $breadcrumb['title'] }}</a></li>--}}
                    {{--@endforeach--}}
                    {{--</ol>--}}
                    {{--</nav>--}}
                    {{--@endif--}}

                    @if(request()->segment(1) == 'lease')
                        @include('layouts._addlease_steps')
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    @else
        @yield('content')
    @endif

    @if(auth()->check())
        @include('layouts._raise_ticket')
    @endif

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('assets/plugins/owlcarousel/owl.carousel.js') }}"></script>
<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')}
        });


    });


</script>
@yield('footer-script')
@yield('inside-script')
<script>
    $('.ul_carousel').owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        autoplay: false,
        autoplayTimeout: 3000,
        smartSpeed: 1500,
        autoplayHoverPause: true,
        items: 10,

        responsive: {
            1350: {
                items: 10,
            },
            1280: {
                items: 8,
            },
            1024: {
                items: 6,
            },
            768: {
                items: 4,
            },
        }

    });

    $('#buildYourPlan_Form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'post',
            dataType: 'json',
            beforeSend: function () {
                $('.buildyourplan_success').html('').hide();
                $('.error').remove();
            },
            success: function (response) {
                if (response['status']) {
                    $('.buildyourplan_success').html(response.message).show();
                    $('#buildYourPlan_Form')[0].reset();
                } else {
                    var errors = response.errors;
                    $.each(errors, function (i, e) {
                        $("select[name='" + i + "'] , input[name='" + i + "']").after('<span class="error">' + e + '</span>');
                    });
                }
            }
        })
    });

</script>


<script type="text/javascript">
    $(window).on("load", function () {
        var index = 0;
        $('.owl-item').each(function (i, e) {
            if ($(this).children('li').children('a').hasClass('active')) {
                return false;
            } else {
                index = index + 1;
            }
        });
        $(".ul_carousel").trigger("to.owl.carousel", [index, 1, true]);
    });
</script>
@if(env('BETA'))
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script>
        $('.beta_version').on('click', function () {
            bootbox.alert({
                title: "Beta Version Disclaimer",
                className: "beta_version_modal",
                message: "Please note this is a Beta release version, some sections are still under process of the additions. However, WITSYNC is taking all measures to give you a better experience but there may be possibility that you may encounter any unknown bug or any function not working. In case you find any such issues or problem, we request you to immediately email us at info@witsync.co or press contact us bar at footer to submit issues faced by you. Our team will support to resolve your issues on earliest priority. \n" +
                    "\n" +
                    "We thank you for your understanding."
            });
        });
    </script>
@endif
<div id="overlay">
    <div id="text">
        <i class="fa fa-circle-o-notch fa-spin" style="font-size:100px; text-align: center"></i><br/>
        Processing your request please wait....
    </div>
</div>
</body>
</html>
