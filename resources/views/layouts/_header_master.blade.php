<header id="header">
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-sm-5">
                    <ul class="social-share">
                        <li><a href="javascript:void(0);"><i class="fa fa-globe"></i> Global</a></li>
                        <li><a href="mailto:info@witsync.co"><i class="fa fa-envelope-o"></i> info@witsync.co</a></li>
                    </ul>
                </div>
                <div class="col-sm-5">
                    <div class="social">
                        <ul class="social-share">

                            @guest
                                <li><a href="{{ route('login') }}"><i class="fa fa-lock"></i> Login</a></li>
                                {{--<li><a href="{{ route('contactus') }}"><span class="rg-btn">Contact us</span></a></li>--}}
                                {{--<li><a href="javascript:void(0);" data-toggle="modal" data-target="#leasing_software"><span class="rg-btn">Register</span></a></li>--}}
                                <li><a href="{{ route('register') }}"><span class="rg-btn">Register</span></a></li>
                            @else
                                @if(auth()->user()->parent_id == '0')
                                    <li><a href="{{route('settings.profile.index')}}"><i class="fa fa-user"></i>My Profile</a></li>
                                @endif
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out"></i>
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            @endguest

                        </ul>
                    </div>
                </div>
            </div>
        </div><!--/.container-->
    </div><!--/.top-bar-->

    <nav class="navbar navbar-inverse" role="banner">
        <div class="container">
            <div class="navbar-header navbarBeta">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><img src="{{ asset('master/images/logo.png') }}" alt="logo" width="200"></a>
                @if(env('BETA'))
                    <span style="cursor:pointer;" class="badge badge-success beta_version">Beta Version</span>
                @endif
            </div>

            <div class="collapse navbar-collapse navbar-right">
                <ul class="nav navbar-nav">
                    <li><a href="/">Home</a></li>
                    <li><a href="{{ route('information.about') }}">About Us</a></li>
                    <li><a href="{{ route('information.services') }}">Services</a></li>
                    <li><a href="{{ route('information.services') }}">Services</a></li>
                    <li><a href="{{ route('master.leasingsoftware.index') }}">Leasing Software</a></li>
                    <li class="dropdown">
                        <a href="javascript: void(0);" class="dropdown-toggle" >Pricing <i class="fa fa-angle-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('master.pricing.index') }}">Leasing Software</a></li>
                            <li><a href="{{ route('master.pricing.vatelearning') }}">VAT E-Learning</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('information.eLearning') }}">E-Learning</a></li>
                    <li><a href="{{ route('contactus') }}">Contact</a></li>
                </ul>
            </div>
        </div><!--/.container-->
    </nav><!--/nav-->

</header><!--/header-->