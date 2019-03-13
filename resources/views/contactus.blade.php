@extends('layouts.master')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')

    <section class="leasing-banner">
        <img src="{{ asset('master/images/contact.jpg') }}" class="img-responsive">
    </section>


    <section class="cnt-page">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>Contact Us</h2>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="box">
                        <h3>Delhi Head Ofice</h3>
                        <p>Delhi Head office –40 B,
                            Ground Floor, LP Block,
                            Maurya Enclave, Pitam Pura,
                            Delhi – 110088, India </p>
                    </div>

                    <div class="box">
                        <h3>UAE Branch Office </h3>
                        <p>Business Center, Al Shmookh Building,
                            UAQ Free Trade Zones, Umm Al Quwain,
                            United Arab Emirates</p>
                    </div>

                    <div class="box">
                        <a href="mailto:info@witsync.co"><i class="fa fa-envelope-o"></i>info@witsync.co</a>
                    </div>

                </div>
                <div class="col-md-8">
                    <form method="POST" action="{{ route('contactus') }}">
                        {{ csrf_field() }}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-warning">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="name" id="name" class="form-control input-lg"
                                           placeholder="Enter Your Name">
                                    @if ($errors->has('name'))
                                        <span class="help-block error">
                                            {{ $errors->first('name') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control input-lg"
                                           placeholder="Enter Your Email">
                                    @if ($errors->has('email'))
                                        <span class="help-block error">
                                            {{ $errors->first('email') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="phone" class="form-control input-lg"
                                           placeholder="Enter Your Phone">
                                    @if ($errors->has('phone'))
                                        <span class="help-block error">
                                            {{ $errors->first('phone') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <textarea name="comments" class="form-control input-lg" placeholder="Message..."
                                              ></textarea>
                                    @if ($errors->has('comments'))
                                        <span class="help-block error">
                                            {{ $errors->first('comments') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <button class="btn btn1" type="submit">Send Message <i class="fa fa-paper-plane"></i>
                                </button>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>

    <section style="padding: 0;">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6999.103402093331!2d77.146646!3d28.703054000000005!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d03d49bcf93f7%3A0x315788e1be5c5b41!2sMaurya+Enclave%2C+Hotel+City+Park+Rd%2C+FP+Block%2C+Block+JP%2C+Poorvi+Pitampura%2C+Pitampura%2C+Delhi%2C+110088!5e0!3m2!1sen!2sin!4v1548492057091"
                style="width: 100%;" height="460" frameborder="0" style="border:0" allowfullscreen></iframe>
    </section>

@endsection
@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script>
        $(function () {
            $('input[name="authorised_person_dob"]').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:-18"
            });
        });

        $(document).ready(function () {
            $("#country").on('change', function () {

            });
        });
    </script>
@endsection