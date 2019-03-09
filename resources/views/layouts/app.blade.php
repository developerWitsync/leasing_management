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
</head>
<body>
    <div>
    @if(request()->segment('1') != 'login' && request()->segment('1') != 'register')
        <nav class="navbar navbar-default navbar-static-top">
            <div class="hdrTop">
                
                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand logoBx" href="{{ url('/') }}">
                        <img src="{{ asset('assets/images/logo2.png') }}" />
                    </a>
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
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                        {{ Auth::user()->authorised_person_name }} | {{ getParentDetails()->legal_entity_name }} <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu">
                                        @if(auth()->user()->parent_id == '0')
                                            <li> <a href="{{route('settings.profile.index')}}">My Profile</a></li>
                                        @endif
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
                                <a class="dash_navicon" href="javascript:void(0)">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                                    <span><i class="fa fa-angle-double-left" aria-hidden="true"></i>Move sidebar</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="DashRight">
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

    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('assets/plugins/owlcarousel/owl.carousel.js') }}"></script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
            });

            

        } );

        

    </script>
    @yield('footer-script')
    @yield('inside-script')
    <script>
    $('.ul_carousel').owlCarousel({
                loop:false,
                margin:10,
                nav:true,
                autoplay: false,
                autoplayTimeout:3000,
                smartSpeed:1500,
                autoplayHoverPause: true,
                items:10,
 
                responsive : {
                    1350 : {
                        items:10,
                    },
                    1280 : {
                        items:8,
                    },
                    1024 : {
                        items:6,
                    },
                    768 : {
                        items:4,
                    },
                } 
                
            });

    $('#buildYourPlan_Form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            data : $(this).serialize(),
            type : 'post',
            dataType : 'json',
            beforeSend: function(){
                $('.buildyourplan_success').html('').hide();
                $('.error').remove();
            },
            success : function(response){
                if(response['status']){
                    $('.buildyourplan_success').html(response.message).show();
                    $('#buildYourPlan_Form')[0].reset();
                } else {
                    var errors = response.errors;
                    $.each(errors, function(i, e){
                        $("select[name='"+i+"'] , input[name='"+i+"']").after('<span class="error">'+e+'</span>');
                    });
                }
            }
        })
    });

    </script>
</body>
</html>
