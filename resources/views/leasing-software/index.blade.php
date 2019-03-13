@extends('layouts.master')
@section('title')
    Leasing Software
@endsection
@section('header-styles')

@endsection
@section('content')

    <section id="main-slider" class="no-margin">
        <div class="carousel slide">
            <!-- <ol class="carousel-indicators">
                <li data-target="#main-slider" data-slide-to="0" class="active"></li>
                <li data-target="#main-slider" data-slide-to="1"></li>
                <li data-target="#main-slider" data-slide-to="2"></li>
                <li data-target="#main-slider" data-slide-to="3"></li>
            </ol> -->
            <div class="carousel-inner">
                <div class="item active" style="background-image: url({{ asset('master/images/Banner1_new_02.jpg') }})">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-8">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1">Get Ready with the </h1>
                                    <h1 class="animation animated-item-1">New Lease Accounting Standard</h1>
                                    <br>
                                    <h2 class="animation animated-item-1">IFRS 16 | IND AS-116 | MFRS 16 | SFRS(I) 16</h2>
                                </div>
                            </div>

                            <div class="col-md-5 hidden-xs animation animated-item-4 pull-right">
                                <div class="slider-img">
                                    <!-- <img src="images/slider/ri-img2.jpeg?ver=2" class="img-responsive"> -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div><!--/.carousel-inner-->
        </div><!--/.carousel-->
        <!-- <a class="prev hidden-xs" href="#main-slider" data-slide="prev">
            <i class="fa fa-chevron-left"></i>
        </a>
        <a class="next hidden-xs" href="#main-slider" data-slide="next">
            <i class="fa fa-chevron-right"></i>
        </a> -->
    </section><!--/#main-slider-->


    <section class="ser lease" style="padding-bottom: 0;">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>Leasing Management Software</h2>
                <!-- <h2>Lessee's Leasing Management Software</h2> -->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">A software tool to easily manage any type of Lease Assets</h2>
                </div>
                <div class="col-md-12  wow fadeInDown">
                    <div class="carousel-wrap">
                        <div class="owl-carousel one">
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/fd1/airline.jpeg?ver=1') }}"><p>Airline</p></a></div>
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/fd1/land.jpeg?ver=1') }}"><p>Land</p></a></div>
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/fd1/commercial.jpeg?ver=1') }}"><p>Commercial Property</p></a></div>
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/fd1/machinery.jpeg?ver=1') }}"><p>Plant & Machinery</p></a></div>
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/fd1/intangibles.jpeg?ver=1') }}"><p>Intangibles</p></a></div>
                            <div class="item"><a href="javascript:void(0);"><img src="{{ asset('master/images/fd1/agricultural-assets.jpeg?ver=1') }}"><p>Agricultural Assets</p></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>
    </section>



    <section class="g-acc">
        <div class="container">
            <div class="col-md-8 col-md-offset-2 center wow fadeInDown">
                <h2>Duly Compliant with New Accounting Standard on Leases</h2>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="card img">
                        <div  class="overl">
                            <h2>IFRS 16 Leasing Software</h2>
                            <br><br><br>
                            <a href="javascript:void(0);" class="btn btn1">View More Detail <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card img">
                        <div  class="overl">
                            <h2>IND AS 116 Leasing Software</h2>
                            <br><br><br>
                            <a href="javascript:void(0);" class="btn btn1">View More Detail <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card img">
                        <div  class="overl">
                            <h2>MFRS 16  Leasing Software</h2>
                            <br><br><br>
                            <a href="javascript:void(0);" class="btn btn1">View More Detail <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card img">
                        <div  class="overl">
                            <h2>SFRS(I) 16 Leasing Software</h2>
                            <br><br><br>
                            <a href="javascript:void(0);" class="btn btn1">View More Detail <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- new add -->

    <section class="ser lease lease_30">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>Leasing Software</h2>
            </div>
            <div class="row lease_30_a">
                <div class="col-sm-6">
                    <br>
                    <h2>The Platform for Lessee's</h2>
                    <h4>Manage Your Leases efficiently and Professionally</h4>
                    <br>
                    <p>The software is designed for lessee’s in compliance with IFRS-16 on leases. Build to streamline your  administrative additional workflow to enable you to easily value and manage your leases on a single destination with full disclosure from lease start to lease end.</p>
                    <div><br></div>
                    <a href="javascript:void(0);" class="btn btn1" data-toggle="modal" data-target="#leasing_software">Subscribe Now <i class="fa fa-angle-right"></i></a>
                    <a href="javascript:void(0);" class="btn btn2" data-toggle="modal" data-target="#leasing_software">Try Now for Free<i class="fa fa-angle-right"></i></a>
                </div>
                <div class="col-sm-6"><img src="{{ asset('master/images/Leasing.jpeg') }}" class="img-responsive"></div>
            </div>
        </div>
    </section>

    <section class="abc1 s_teem " style="background: url({{ asset('master/images/teem_bg.jpeg') }}) no-repeat fixed;">
        <div class="center wow fadeInDown">
            <h2>The Team Behind the Leasing Software</h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 wow fadeInDown">
                    <div class="carousel-wrap">
                        <div class="owl-carousel two">
                            <div class="item">
                                <div class="col-md-3 col-sm-3 col-xs-12 wow fadeInDown text-center">
                                    <img src="{{ asset('master/images/teem/Sajal.JPG') }}">
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12 wow fadeInDown">
                                    <div class="card">
                                        <div class="cnt">
                                            <h2>Sajal Arora <br><small>Founder & CEO</small></h2>
                                            <p class="">He is a qualified Chartered Accountant from India with over 13 years of practical professional experience in almost all fields of integrated finance – IFRS’s, Accounting, Auditing, Taxation, Valuations, Cost & Benefit Analysis etc. He understands the transitional & subsequent challenges in the process of lease valuations and to simply the processes he invested over 9 months in the development of the leasing software duly compliant with principles as set in IFRS 16  to make an effective and easy tool for companies and its users. </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="col-md-3 col-sm-3 col-xs-12 wow fadeInDown text-center">
                                    <img src="{{ asset('master/images/teem/Shahnawaz-Khan.png') }}">
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12 wow fadeInDown">
                                    <div class="card">
                                        <div class="cnt">
                                            <h2>Shahnawaz Khan <br><small>Chartered Accountant</small></h2>
                                            <p>He is a Chartered Accountant from ICAI and ICAEW with over 18 years of professional experience specializing in financial services, Statutory Audits, Due Diligence, Compliance, and Regulatory Reviews and facilitated the number of training on IFRSs, insurance, and banking.  He works as a partner with Grant Thornton.  He reviewed in his individual capacity all key features of the WISTYNC's Leasing Software and provided his valuable inputs based on industry trends and best practices.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="col-md-3 col-sm-3 col-xs-12 wow fadeInDown text-center">
                                    <img src="{{ asset('master/images/teem/Aaditya-Arora.jpg') }}">
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12 wow fadeInDown">
                                    <div class="card">
                                        <div class="cnt">
                                            <h2>Aaditya Arora <br><small>Research Analyst</small></h2>
                                            <p>He holds Post Graduate Diploma in Management (Specialized in Finance) and possess over 6 years of practical experience in finance. He has actively supported in the research and analysis of the key elements in IFRS 16 and has done the extensive review of the basic functions of Leasing software. He is also part of the WITSYNC in assisting clients (GCC and Asia Pacific) with accounting, auditing and taxation assignments.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="col-md-3 col-sm-3 col-xs-12 wow fadeInDown text-center">
                                    <img src="{{ asset('maseter/images/teem/Sajeev-Surendran.png') }}">
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12 wow fadeInDown">
                                    <div class="card">
                                        <div class="cnt">
                                            <h2>Sajeev Surendran <br><small>Independent External Reviewer</small></h2>
                                            <p>He is a Chartered Accountant from ICAI and a Certified Management Accountant with over 15 years of professional experience specializing in financial and management consulting services. His expertise includes IFRS, Accounting, Forensic and Special Audits, Taxation and internal controls including process re-engineering. He is working as Managing Partner at Finpro Consulting, Sultanate of Oman.  He independently reviewed the WISTYNC's Leasing Software functions, features, internal control checks in compliance with IFRS 16.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="s_advantages">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>The advantages of Leasing Software</h2>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="box s_bg_1">
                        <i class="fa fa-cog"></i>
                        <h2>Simple & Intuitive</h2>
                        <p>Create your account and benefit immediately of Leasing Software. Easy to Use, you will take in hand with full autonomy.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box s_bg_2">
                        <i class="fa fa-money"></i>
                        <h2>Affordable</h2>
                        <p>The subscription offers of Leasing Software are attractive and affordable to all sizes of companies. You have a trial period to test our software.</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box s_bg_3">
                        <i class="fa fa-lightbulb-o"></i>
                        <h2>Innovative</h2>
                        <p>The Leasing Software offers innovative features to value your lease liabilities at present value, get disclosures at reporting period and lock your lease period. Explore full features of the software.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box s_bg_4">
                        <i class="fa fa-thumbs-o-up"></i>
                        <h2>Effective</h2>
                        <p>The Leasing Software offers a powerful set of features to speed up the valuation of your company’s leases and stay compliant with New Lease Accounting Standard.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="abc1" style="background: url({{ asset('master/images/soft.jpeg') }}) no-repeat fixed;">
        <div class="center wow fadeInDown">
            <h2>Features of the Leasing Software</h2>
        </div>
        <div class="container">
            <div class="row">

                <div class="col-md-12  wow fadeInDown">
                    <h3>Features in Figures</h3>
                    <ul class="abc-aa">
                        <li><h1 ><span class="count">1000</span>+</h1> Add Lease Assets</li>
                        <li><h1 ><span class="count">1</span> Dashboard</h1> Track all Your Leases</li>
                        <li>Upto <h1 ><span class="count">100</span> Years</h1> Lease Term</li>
                        <li><h1 ><span class="count">168</span>+</h1> Multiple Currencies</li>
                        <li><h1 ><span class="count">1</span> Stop </h1> Destination for all Your leases</li>
                        <li><h1 ><span class="count">7</span>+</h1> Add Multiple Nature of leases Assets</li>
                        <li><h1 ><span class="count">4</span>+</h1> User Access</li>
                        <li><h1 ><span class="count">1000</span>+</h1> Manage Your Leases With Multiple Locations</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>



    <section class="abc2">
        <div class="container">
            <div class="center wow fadeInDown animated" >
                <h2>Lease Valuation Basis</h2>
            </div>
            <div class="col-md-3">
                <div class="wow fadeInDown animated text-right" data-wow-duration="1000ms" data-wow-delay="300ms" >
                    <div class="skill">
                        <i class="fa fa-user"></i>
                        <p>Own Use or Sub-Lease</p>
                    </div>
                </div>
                <div class="wow fadeInDown animated text-right" data-wow-duration="1000ms" data-wow-delay="300ms" >
                    <div class="skill">
                        <i class="fa fa-user"></i>
                        <p>Lease or Non-Lease Component</p>
                    </div>
                </div>
                <div class="wow fadeInDown animated text-right" data-wow-duration="1000ms" data-wow-delay="300ms" >
                    <div class="skill">
                        <i class="fa fa-user"></i>
                        <p>Fixed or Variable</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6"><img src="{{ asset('master/images/business-plan.jpg') }}" style=""></div>
            <div class="col-md-3">
                <div class="wow fadeInDown animated text-left" data-wow-duration="1000ms" data-wow-delay="300ms" >
                    <div class="skill">
                        <i class="fa fa-user"></i>
                        <p>Termination or Renewal or Purchase Option</p>
                    </div>
                </div>
                <div class="wow fadeInDown animated text-left" data-wow-duration="1000ms" data-wow-delay="300ms" >
                    <div class="skill">
                        <i class="fa fa-user"></i>
                        <p>Short-term or Low Value Lease Asset</p>
                    </div>
                </div>
                <div class="wow fadeInDown animated text-left" data-wow-duration="1000ms" data-wow-delay="300ms" >
                    <div class="skill">
                        <i class="fa fa-user"></i>
                        <p>Initial Direct Cost or Lease Incentives</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="team">
                <div class="center wow fadeInDown animated" >
                    <h2>Get Your Lease Valuation Done Easily and Instantly</h2>

                </div>

                <div class="row clearfix">
                    <div class="col-md-4 col-sm-6">
                        <div class="single-profile-top wow fadeInDown animated" >
                            <div class="media">
                                <div class="media-body">
                                    <h3>01</h3>
                                    <h4>Manage Your Settings</h4>
                                </div>
                            </div><!--/.media -->
                            <p>Complete Your Settings in accordance with the all Predefined fields applicable as per Lease Accounting Standard</p>
                        </div>
                    </div><!--/.col-lg-4 -->


                    <div class="col-md-4 col-sm-6 col-md-offset-2">
                        <div class="single-profile-top wow fadeInDown animated" >
                            <div class="media">
                                <div class="media-body">
                                    <h3>02</h3>
                                    <h4>Input Your Lease Details</h4>
                                </div>
                            </div><!--/.media -->
                            <p>Place Your Lease Inputs</p>
                            <ul>
                                <li>Enter Lease Payments</li>
                                <li>Add Residual Value Guarntee</li>
                                <li>Apply Lease Escalation</li>
                                <li>Select Appropriate Discount Rate</li>
                                <li>Update Your Lease Cash Flows</li>
                            </ul>
                        </div>
                    </div><!--/.col-lg-4 -->
                </div> <!--/.row -->
                <div class="row team-bar">
                    <div class="first-one-arrow hidden-xs">
                        <hr>
                    </div>
                    <div class="first-arrow hidden-xs">
                        <hr> <i class="fa fa-angle-up"></i>
                    </div>
                    <div class="second-arrow hidden-xs">
                        <hr> <i class="fa fa-angle-down"></i>
                    </div>
                    <div class="third-arrow hidden-xs">
                        <hr> <i class="fa fa-angle-up"></i>
                    </div>
                    <div class="fourth-arrow hidden-xs">
                        <hr> <i class="fa fa-angle-down"></i>
                    </div>
                </div> <!--skill_border-->

                <div class="row clearfix">
                    <div class="col-md-4 col-sm-6 col-md-offset-2">
                        <div class="single-profile-bottom wow fadeInUp animated" >
                            <div class="media">
                                <div class="media-body">
                                    <h3>03</h3>
                                    <h4>Get Initial Lease Valuation</h4>
                                </div>
                            </div><!--/.media -->
                            <p>Get Your Initial Lease Valuation Instantly</p>
                            <ul>
                                <li>Present Value of Lease Liability</li>
                                <li>Value of Lease Asset</li>
                                <li>Impairement Test</li>
                                <li>Review All Your Lease Details</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-md-offset-2">
                        <div class="single-profile-bottom wow fadeInUp animated">
                            <div class="media">
                                <div class="media-body">
                                    <h3>04</h3>
                                    <h4>Lease Modifications</h4>
                                </div>
                            </div><!--/.media -->
                            <p>Manage Your Lease Modification Easily</p>
                            <ul>
                                <li>Manage any changes in your Lease Details with Lessor Upto Lease Term</li>
                                <li>Revalue Your Lease Subsequently for any change in Lease Details including Change in Discount Rates</li>
                                <li>Classify Your Subsequent Lease Modifications - Internally & With Lessor</li>
                            </ul>
                        </div>
                    </div>
                </div>  <!--/.row-->
            </div>
        </div>
    </section>


    <section class="shortcode-item  new-sec" style="background: url({{ asset('master/images/track.jpeg') }}) no-repeat fixed;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-md-offset-1">
                    <div class="tab-wrap">
                        <div class="media">
                            <div class="parrent pull-left">
                                <ul class="nav nav-tabs nav-stacked">
                                    <li class=""><a href="#tab1" data-toggle="tab" class="analistic-01">Lease Cash Flows</a></li>
                                    <li class=""><a href="#tab2" data-toggle="tab" class="analistic-02">Track Lease Balances</a></li>
                                    <li class=""><a href="#tab3" data-toggle="tab" class="tehnical">Disclosures</a></li>
                                    <li class="active"><a href="#tab4" data-toggle="tab" class="tehnical">Reports</a></li>
                                    <li class=""><a href="#tab5" data-toggle="tab" class="tehnical">Reconcile</a></li>
                                </ul>
                            </div>

                            <div class="parrent media-body">
                                <div class="tab-content">
                                    <div class="tab-pane" id="tab1">
                                        <div class="media">
                                            <div class="media-body">
                                                <h4 class="animation anm_1">Timely Update Lease Cash Flows</h4>
                                                <p class="animation anm_2">Timely Update Your Lease Cash Flows to Keep Accurate Lease Valuation</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab2">
                                        <div class="media">
                                            <div class="media-body">
                                                <h4 class="animation anm_1">Easily Track Your Lease Balances</h4>
                                                <ul class="animation anm_2">
                                                    <li>Present Value of Lease Liability</li>
                                                    <li>Accrued Interest</li>
                                                    <li>Value of Lease Asset</li>
                                                    <li>Depreciation on Lease Asset</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab3">
                                        <div class="media">
                                            <div class="media-body">
                                                <h4 class="animation anm_1">Get Your Lease Disclosures</h4>
                                                <p class="animation anm_2">Get Completely Filled Lease Disclosures Details for Every Audit Period with Lease Maturity Analysis</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane active" id="tab4">
                                        <div class="media">
                                            <div class="media-body">
                                                <h4 class="animation anm_1">Get Lease Reports You Require</h4>
                                                <div class="col-md-7 animation anm_2">
                                                    <img src="{{ asset('master/images/dashboard.jpg') }}" class="img-responsive">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab5">
                                        <div class="media-body">
                                            <h4 class="animation anm_1">Reconcile Lease Balances With Accounting</h4>
                                            <p class="animation anm_2">Reconcile Your Lease Balances on a Regular Basis with your Accounting Ledgers</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="end-sec">
        <div class="container" style="width: 100%;">
            <div class="">
                <div class="col-md-6">
                    <h1>Manage all your Leases and Stay Compliant with the New Accounting Standard on Leases</h1>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('master/images/bg2.jpeg') }}" class="img-responsive">
                </div>
            </div>
        </div>
    </section>


    <section class="more-features">
        <div class="container">
            <div class="center wow fadeInDown animated" >
                <h2>Explore Extra More Features</h2>

            </div>

            <div class="row">
                <div class="features">
                    <div class="col-md-4 col-sm-6 wow fadeInDown animated" >
                        <div class="feature-wrap">
                            <i class="fa fa-life-ring"></i>
                            <h3>Your data safe and second. The data entered gets encrypted.</h3>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInDown animated" >
                        <div class="feature-wrap">
                            <i class="fa fa-money"></i>
                            <h3>You can easily manage your leases in multiple currencies.</h3>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInDown animated" >
                        <div class="feature-wrap">
                            <i class="fa fa-usd"></i>
                            <h3>Get automated currency exchange rate</h3>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInDown animated" >
                        <div class="feature-wrap">
                            <i class="fa fa-users"></i>
                            <h3>Define multiple users with roles</h3>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInDown animated">
                        <div class="feature-wrap">
                            <i class="fa fa-lock"></i>
                            <h3>Lock your lease valuations with audit complete</h3>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInDown animated" >
                        <div class="feature-wrap">
                            <i class="fa fa-cogs"></i>
                            <h3>Configure your common lease assets settings</h3>
                        </div>
                    </div><!--/.col-md-4-->
                </div><!--/.services-->
            </div><!--/.row-->
        </div><!--/.container-->
    </section>

@endsection