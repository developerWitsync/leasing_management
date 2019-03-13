@extends('layouts.master')
@section('title')
    Witsync Lease Management | About Us
@endsection
@section('header-styles')

@endsection
@section('content')


    <section class="leasing-banner">
        <img src="{{ asset('master/images/about-us-banner.jpeg') }}" class="img-responsive">
    </section>


    <section class="about-page">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>About Us</h2>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 wow fadeInDown animated">
                    <div class="box">
                        <h3>Who We Are</h3>
                        <p>At WITSYNC, we focus on creating things differently and innovatively. Building Intangible Excellence in Education, Information Advisory and Business Support Function globally using advanced unique, innovative technological features.</p>
                        <br>
                        <h3>What We Do</h3>
                        <p>We immensely focus on global research and resources to create for our customers a quality and sustainable value-oriented solutions to their problems. With a dedicated Research & Development team, we always exploring unidentified gaps and new ways of doing things to redefine with better systems, controls, user-friendly, innovative and synchronized features</p>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-lg-6 wow fadeInDown animated">
                    <img src="{{ asset('master/images/about-us.jpeg') }}">
                </div>
            </div>
        </div>
    </section>

@endsection
@section('footer-script')
    <script src="{{ asset('master/js/wow.min.js') }}"></script>
@endsection