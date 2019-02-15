@extends('layouts.master')
@section('title')
    Witsync Lease Management
@endsection
@section('header-styles')

@endsection
@section('content')
    <section id="main-slider" class="no-margin">
        <div class="carousel slide">
            <ol class="carousel-indicators">
                <li data-target="#main-slider" data-slide-to="0" class="active"></li>
                <li data-target="#main-slider" data-slide-to="1"></li>
                <li data-target="#main-slider" data-slide-to="2"></li>
                <li data-target="#main-slider" data-slide-to="3"></li>
                <li data-target="#main-slider" data-slide-to="4"></li>
            </ol>
            <div class="carousel-inner">

                <div class="item active" style="background-image: url(images/slider/img1.jpeg)">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1">Welcome to WITSYNC</h1>
                                    <h2 class="animation animated-item-2">FINTECH Solutions   |  Business Consultancy</h2>
                                    <!-- <a class="btn-slide animation animated-item-3" href="#">Read More</a> -->
                                </div>
                            </div>

                            <div class="col-md-5 hidden-xs animation animated-item-4 pull-right">
                                <div class="slider-img">
                                    <img src="{{ asset('master/images/slider/ri-banner.jpg') }}" class="img-responsive">
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!--/.item-->

                <div class="item" style="background-image: url('{{ asset("master/images/slider/img3.jpeg") }}')">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1">Lessee Leasing Management</h1>
                                    <h2 class="animation animated-item-2">An integrated solution for all your business lease assets. Manage your leases and generate detailed reporting from lease start to lease end.</h2>
                                    <!-- <a class="btn-slide animation animated-item-3" href="#">Read More</a> -->
                                </div>
                            </div>

                            <div class="col-md-5 hidden-xs animation animated-item-4 pull-right">
                                <div class="slider-img">
                                    <img src="{{ asset('master/images/slider/ri-img1.jpeg?ver=2') }}" class="img-responsive">
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!--/.item-->

                <div class="item" style="background-image: url('{{ asset("master/images/slider/img2.jpeg") }}')">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1">Appraise Your Lease Assets</h1>
                                    <h2 class="animation animated-item-2">Produce Accounting Standard’s compliant lease valuation reports using discounting and capitalization methodologies – ideal for any type of lease assets – high value, commercial, industrial, and retail properties and plant machineries.</h2>
                                    <!-- <a class="btn-slide animation animated-item-3" href="#">Read More</a> -->
                                </div>
                            </div>

                            <div class="col-md-5 hidden-xs animation animated-item-4 pull-right">
                                <div class="slider-img">
                                    <img src="{{ asset('master/images/slider/ri-img2.jpeg?ver=2') }}" class="img-responsive">
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!--/.item-->

                <div class="item" style="background-image: url('{{ asset("master/images/slider/img4.jpeg") }}')">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1">Take Control of Your Lease Portfolio’s</h1>
                                    <h2 class="animation animated-item-2">Get a company level view of your lease portfolio and its valuation. Provide insightful business intelligence.</h2>
                                    <!-- <a class="btn-slide animation animated-item-3" href="#">Read More</a> -->
                                </div>
                            </div>
                            <div class="col-md-5 hidden-xs animation animated-item-4 pull-right">
                                <div class="slider-img">
                                    <img src="{{ asset('master/images/slider/ri-img3.jpeg?ver=2') }}" class="img-responsive">
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/.item-->
                <div class="item" style="background-image: url({{ asset("master/images/slider/img5.jpeg") }})">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1">Compliant with New Accounting Standard’s on Lease Accounting</h1>
                                    <h2 class="animation animated-item-2">Get your Leasing Management Software today</h2>
                                    <!-- <a class="btn-slide animation animated-item-3" href="#">Read More</a> -->
                                </div>
                            </div>
                            <div class="col-md-5 hidden-xs animation animated-item-4 pull-right">
                                <div class="slider-img">
                                    <ul>
                                        <li><i class="fa fa-check-square-o"></i> Reliable</li>
                                        <li><i class="fa fa-check-square-o"></i> Scalable</li>
                                        <li><i class="fa fa-check-square-o"></i> Transparent</li>
                                        <li><i class="fa fa-check-square-o"></i> Real Time</li>
                                        <li><i class="fa fa-check-square-o"></i> Trusted</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--/.item-->
            </div><!--/.carousel-inner-->
        </div><!--/.carousel-->
        <a class="prev hidden-xs" href="#main-slider" data-slide="prev">
            <i class="fa fa-chevron-left"></i>
        </a>
        <a class="next hidden-xs" href="#main-slider" data-slide="next">
            <i class="fa fa-chevron-right"></i>
        </a>
    </section><!--/#main-slider-->


    <!--
      <section class="tax about">
            <div class="container">
                <div class="center wow fadeInDown">
                    <h2>About Us</h2>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 text-center">
                       <p>At WITSYNC, we focus on creating things differently and innovatively. Building Intangible Excellence in Education, Information Advisory and Business Support Function globally using advanced technological features. We immensely focus on global research and resources to create for our customers a quality and sustainable value-oriented solutions to their problems.</p>
                    </div>
                </div>
            </div>
        </section> -->



    <section class="explore">
        <div class="container">
            <div class="row">
                <div class="col-md-6 wow fadeInDown">
                    <div class="center">
                        <h2>Leasing Software</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 wow fadeInDown">
                    <h4>Introducing Lessee’s Leasing Management Software</h4>
                    <p class="lead">A single destination for all your leases</p>

                    <p>Fully Compliant with New Global Accounting Standard’s on Lease Accounting. Manage all your leases including short-term or low value lease asset efficiently and professionally.</p>

                    <p>Stay Compliant with New Accounting Standard on leases.</p>
                    <br>
                    <a href="leasing-software.php" class="btn btn1">View More Detail <i class="fa fa-angle-right"></i></a>
                    <a href="javascript:void(0);" class="btn btn2">Try Now <i class="fa fa-angle-right"></i></a>
                </div>
                <div class="col-md-6 wow fadeInRight">
                    <img src="{{ asset('master/images/temp.jpg') }}" class="img-responsive">
                    <!-- <iframe src="https://www.youtube.com/embed/oqdiN5cFgZs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->

                </div>
            </div>
        </div>
    </section>


    <section class="tax">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>VAT E-Learning</h2>
            </div>
            <div class="row">
                <div class="col-md-5 video wow fadeInLeft">
                    <video muted="true" controls autoplay="">
                        <source src="{{ asset('master/video/Motion-Banner-witsync.mp4') }}" type="video/mp4">
                    </video>
                    <!-- 1<img src="images/vat.jpeg" class="img-responsive"> -->
                </div>
                <div class="col-md-7 wow fadeInRight">
                    <!-- <h3>VAT BASICS & UNDERSTANDING</h3> -->

                    <h4>Vat Basics & Understanding</h4>
                    <ul>
                        <li>All Basic Concepts in VAT</li>
                        <li>How VAT Works</li>
                        <li>VAT Technicale Understangs</li>
                        <li>OECD and Vat</li>
                        <li>Quiz and Certificate of Completion</li>
                    </ul>
                    <div class="text-right">
                        <a href="https://www.witsyncim.com/e-learning" class="btn btn2">View More Details <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="ser">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>Services We Offer</h2>
            </div>
            <div class="row">
                <div class="col-md-12  wow fadeInDown">
                    <div class="carousel-wrap">
                        <div class="owl-carousel">
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/owl-img/E-Learning.jpg?ver=2') }}"><p>E-Learning Concepts</p></a></div>
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/owl-img/taxation-advisory.jpg?ver=2') }}"><p>Taxation Advisory</p></a></div>
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/owl-img/management-consultancy.jpeg?ver=2') }}"><p>Management Consultancy</p></a></div>
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/owl-img/accounting-servicees.jpeg?ver=2') }}"><p>Accounting Servicees</p></a></div>
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/owl-img/business-software.jpg?ver=2') }}"><p>Business Software</p></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="feature">
        <div class="container-fluid full">
            <div class="">
                <div class="col-md-6 wow fadeInLeft">
                    <!-- <iframe src="https://www.youtube.com/embed/yAoLSRbwxL8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
                    <video muted="true" controls autoplay="">
                        <source src="{{ asset('master/video/home-video.mp4') }}" type="video/mp4">
                    </video>
                    <button id="unmute_trigger" style="display: none;" onclick="document.getElementById('video_tag').muted=false;"></button>
                    <!-- <div align="center" class="embed-responsive embed-responsive-16by9">
                       <video autoplay loop unselectable="on" class="embed-responsive-item" style="user-select: none;">
                           <source unselectable="on" src="video/home-video.mp4" type="video/mp4" style="user-select: none;">
                       </video>
                   </div> -->
                </div>
                <div class="col-md-6 text-center  wow fadeInRight">
                    <h2 class="plt">BUILDING <span> ONE STOP</span><br>  PLATFORM.</h2>
                </div>
            </div>
        </div><!--/.container-->
        <div class="clearfix"></div>
    </section><!--/#feature-->
@endsection
@section('footer-script')

    <script src="{{ asset('master/js/wow.min.js') }}"></script>
    <script src="{{ asset('master/owl/owl.carousel.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var owl = $('.owl-carousel');
            owl.owlCarousel({
                margin: 10,
                nav: true,
                loop: true,
                autoplay:true,
                autoplayTimeout:2000,
                autoplayHoverPause:true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    }
                }
            })
        })
    </script>
    <!-- for jquery count function -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('.count').each(function () {
                $(this).prop('Counter',0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 5000,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });
        });
    </script>

@endsection
