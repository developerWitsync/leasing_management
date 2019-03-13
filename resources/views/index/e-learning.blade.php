@extends('layouts.master')
@section('title')
    Witsync Lease Management | E-Learning
@endsection
@section('header-styles')

@endsection
@section('content')


    <section class="leasing-banner">
        <img src="{{ asset('master/images/about-us-banner.jpeg') }}" class="img-responsive">
    </section>


    <section class="tax e-learning_page">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>E-Learning Concepts</h2>
                <h4><br>Vat Basics & Understanding</h4>
                <h4 class="text-center "><small>( Version 1 )</small></h4>
            </div>
            <div class="row">
                <div class="col-md-6 video wow fadeInLeft">
                    <img src="{{ asset('master/video/ezgif.com-video-to-gif.gif') }}" class="img-responsive">
                    <!-- <video muted="true" loop="" autoplay="" onselectstart="return false">
                     <source src="video/Motion-Banner-witsync.mp4" type="video/mp4">
                     </video> -->

                    <center>
                        <a class="btn sub-btn" href="https://www.witsyncim.com/register" target="_blank">SUBSCRIBE NOW FOR USD $ 60</a>
                    </center>
                </div>
                <div class="col-md-6 wow fadeInRight">
                    <h4>Benefits Of Subscription</h4>
                    <h4><small>Includes: </small></h4>
                    <ul>
                        <li>Lifetime Subscription to VAT E-Learning</li>
                        <li>Access to Full Resources</li>
                        <li>Get Regular Updates</li>
                        <li>Anytime Anywhere Access</li>
                        <li>Certificate on Completion</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- for pricing_table -->
    <?php // include('pricing_table.php'); ?>
    <!-- for pricing_table end -->

    <br>
    <br>
    <br>


@endsection
@section('footer-script')
    <script src="{{ asset('master/js/wow.min.js') }}"></script>
@endsection