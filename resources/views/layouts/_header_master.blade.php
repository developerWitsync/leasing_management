<header id="header">
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <!-- <div class="top-number"><p><i class="fa fa-phone-square"></i>  +91-9910418983 (India)</p></div> -->
                    <ul class="social-share">
                        <li><a href="javascript:void(0);"><i class="fa fa-globe"></i> Global</a></li>
                        <li><a href="javascript:void(0);"><i class="fa fa-envelope-o"></i> info@witsync.co</a></li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <div class="social">
                        <ul class="social-share">

                            @guest
                                <li><a href="{{ route('login') }}"><i class="fa fa-lock"></i> Login</a></li>
                                <!--   <li><a href="{{ route('register') }}">Register</a></li>-->
                                <li><a href="{{ route('contactus') }}"><span class="rg-btn">Contact us</span></a></li>
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

                            {{--<li><a href="{{ route('login') }}"><i class="fa fa-lock"></i> Login</a></li>--}}
                            {{--<li><a href="javascript:void(0);"><span class="rg-btn">Create Account</span></a></li>--}}
                            <!-- <li><a href="javascript:void(0);"><span class="rg-btn">Register</span></a></li> -->
                            <!-- <li><a href="javascript:void(0);"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="javascript:void(0);"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="javascript:void(0);"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="javascript:void(0);"><i class="fa fa-dribbble"></i></a></li>
                            <li><a href="javascript:void(0);"><i class="fa fa-skype"></i></a></li> -->
                        </ul>
                        <!-- <div class="search">
                            <form role="form">
                                <input type="text" class="search-form" autocomplete="off" placeholder="Search">
                                <i class="fa fa-search"></i>
                            </form>
                       </div> -->
                    </div>
                </div>
            </div>
        </div><!--/.container-->
    </div><!--/.top-bar-->

    <nav class="navbar navbar-inverse" role="banner">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./"><img src="{{ asset('master/images/logo.png') }}" alt="logo" width="200"></a>
            </div>

            <div class="collapse navbar-collapse navbar-right">
                <ul class="nav navbar-nav">
                    <li><a href="./">Home</a></li>
                    <li><a href="aboutUs.php">About Us</a></li>
                    <li><a href="services.php">Services</a></li>
                    <!-- <li><a href="javascript:void(0);">Leasing Software</a></li> -->
                    <li class="dropdown">
                        <!-- <a href="leasing-software.php" class="dropdown-toggle" data-toggle="dropdown">Leasing Software <i class="fa fa-angle-down"></i></a> -->
                        <a href="leasing-software.php" class="dropdown-toggle" >Leasing Software <i class="fa fa-angle-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="IFRS-16-Leasing-Software.php">IFRS 16 Leasing Software</a></li>
                            <li><a href="IND-AS-116-Leasing-Software.php">IND AS 116 Leasing Software</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);">E-Learning</a></li>
                    <li><a href="contact-us.php">Contact</a></li>
                </ul>
            </div>
        </div><!--/.container-->
    </nav><!--/nav-->

</header><!--/header-->