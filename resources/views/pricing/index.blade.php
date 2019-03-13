@extends('layouts.master')
@section('title')
    Leasing Software Pricing
@endsection
@section('header-styles')

@endsection
@section('content')

    <section id="main-slider" class="no-margin">
        <div class="carousel slide">
            <div class="carousel-inner">
                <div class="item active" style="background-image: url({{ asset('master/images/banner1_nnew.jpg') }})">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-8">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1">Get Ready with the </h1>
                                    <h1 class="animation animated-item-1">New Lease Accounting Standard</h1>
                                    <br>
                                    <h2 class="animation animated-item-1">IFRS 16 | IND AS-116 | MFRS 16 | SFRS(I)
                                        16</h2>
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

    </section><!--/#main-slider-->


    <!-- for pricing_table -->
    @include('pricing._plans')
    <!-- for pricing_table end -->


@endsection