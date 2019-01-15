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
    @yield('header-styles')
</head>
<body>
    <div id="app">
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
                        <img src="{{ asset('assets/images/logo.png') }}" />
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

        @if(auth()->check())
            <div class="dashOuter clearfix">
                    <div class="dashLeft">
                        <div class="leftmenuHd">
                            <div class="menuHd"><span>Menu</span> <a class="dash_navicon" href="javascript:void(0)"><i class="fa fa-bars" aria-hidden="true"></i></a></div>
                        </div>
                        <div class="leftNav">
                            @include('layouts._sidebar')
                        </div>
                    </div>
                    <div class="DashRight">
                        @if(isset($breadcrumbs ))
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                                        @foreach($breadcrumbs as $breadcrumb)
                                            <li class="breadcrumb-item"><a href="{{ $breadcrumb['link'] }}">{{ $breadcrumb['title'] }}</a></li>
                                        @endforeach
                                </ol>
                            </nav>
                        @endif
                        <div class="itemTab" >
                            <ul>
                                <li>
                                    <a href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon-hover.png') }}" alt="" class="over" >
                                        <span>HoLessor Details</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon2.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon2-hover.png') }}" alt="" class="over" >
                                        <span>Underlying Lease Asset</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon3.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon3-hover.png') }}" alt="" class="over" >
                                        <span>Add Lease Payments</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon4.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon4-hover.png" alt="" class="over" >
                                        <span>Fair Market Value</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon5.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon5-hover.png" alt="" class="over" >
                                        <span>Residual Value Guarantee</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon6.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon6-hover.png" alt="" class="over" >
                                        <span>Termination Option</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon7.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon7-hover.png" alt="" class="over" >
                                        <span>Renewal Option</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon8.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon8-hover.png" alt="" class="over" >
                                        <span>Purchase Option</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon9.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon9-hover.png" alt="" class="over" >
                                        <span>Lease Duration Classified</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon10.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon10-hover.png" alt="" class="over" >
                                        <span>Lease Escalation</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon11.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon11-hover.png" alt="" class="over" >
                                        <span>Select Low Value</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon12.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon12-hover.png" alt="" class="over" >
                                        <span>Select Discount Rate</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon13.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon13-hover.png" alt="" class="over" >
                                        <span>Lease Balances</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon14.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon14-hover.png" alt="" class="over" >
                                        <span>Initial Direct Cost</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon15.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon15-hover.png" alt="" class="over" >
                                        <span>Lease Incentives</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon16.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon16-hover.png" alt="" class="over" >
                                        <span>Lease Valuation</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon17.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon17-hover.png" alt="" class="over" >
                                        <span>Lessor Invoice</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="../../assets/images/breatcrum-icon18.png" class="img" alt="" >
                                        <img src="../../assets/images/breatcrum-icon18-hover.png" alt="" class="over" >
                                        <span>Review & Submit</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                        @yield('content')
                        
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

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
            });
        } );
    </script>
    @yield('footer-script')
    @yield('inside-script')
</body>
</html>
