@extends('layouts.master')
@section('title')
    Witsync Lease Management
@endsection
@section('header-styles')

@endsection
@section('content')


    <section class="leasing-banner">
        <img src="{{ asset('master/images/services.jpg') }}" class="img-responsive">
    </section>

    <section class="ser">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>Services We Offer</h2>
            </div>
            <div class="row">
                <div class="col-md-12  wow fadeInDown">
                    <div class="carousel-wrap">
                        <div class="owl-carousel one">
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



@endsection
@section('footer-script')
    <script src="{{ asset('master/js/wow.min.js') }}"></script>
@endsection