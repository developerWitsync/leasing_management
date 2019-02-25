@extends('layouts.master')
@section('title')
    Witsync Lease Management
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
            <h2>{{ $page->title }}</h2>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 wow fadeInDown animated">
                <div class="box">
                  {!!  $page->description !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footer-script')
    <script src="{{ asset('master/js/wow.min.js') }}"></script>
@endsection