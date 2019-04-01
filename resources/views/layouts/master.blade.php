<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title')</title>

    <!-- core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}">
    <link href="{{ asset('master/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('master/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('master/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('master/css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('master/css/main.css?ver=11') }}" rel="stylesheet">
    <link href="{{ asset('master/css/style.css?ver=11') }}" rel="stylesheet">
    <link href="{{ asset('master/css/responsive.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <!-- owl  -->
    <link rel="stylesheet" href="{{ asset('master/owl/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('master/owl/assets/owl.theme.default.min.css') }}">

    <!--[if lt IE 9]>
    <script src="{{ asset('master/js/html5shiv.js')}}"></script>
    <script src="{{ asset('master/js/respond.min.js') }}"></script>
    <![endif]-->
    <!-- <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png"> -->
    <style>
        #getInTouchWithUs input {
            /*margin-bottom: 0px;*/
        }

        #getInTouchWithUs .error {
            color: red;
        }

        .form-group.required .control-label:after {
            color:red;
            font-family: 'FontAwesome';
            font-weight: normal;
            font-size: 10px;
            content: "\f069";
            position: relative;
            bottom: 9px;
            left: 1px;
        }

        .error {
            color: red;
            font-style: italic;
        }

        .launchinSoonContent{
            padding-top: 20px;
        }

        .launchinSoonContent p{
            font-size: 16px;
            line-height: 25px;
            color: #333333;
        }

        .launchinSoonContent a{
            font-size: 16px;
        }
    </style>
    @yield('header-styles')
    <link rel="shortcut icon" href="{{ asset('master/images/favicon.png') }}">
</head><!--/head-->

<body class="homepage">

@include('layouts._header_master')

@yield('content')

@include('layouts._footer_master')

@if (session('register_not_allowed'))
    <div id="registerNotAllowed" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="text-align: center;color: #19afc8;font-weight: 700;padding-bottom: 10px;">Launching Soon</h4>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <img style="max-width: 100%" src="{{ asset('master/images/launching_soon.png') }}">
                    </div>
                    <div class="col-md-8 launchinSoonContent">
                        <p>Lessee Leasing Mangement Software</p>
                        <p>Duly Compliant with IFRS 16  On Leases</p>
                        <p>A Tool To  Easily Manage Your Lease Assets Valuations</p>
                        <a href="{{ route('contactus') }}">Contact Us</a>
                    </div>
                </div>
                <div class="modal-footer">
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                </div>
            </div>

        </div>
    </div>
@endif

<script src="{{ asset('master/js/jquery.js') }}"></script>
<script src="{{ asset('master/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('master/js/jquery.prettyPhoto.js') }}"></script>
<script src="{{ asset('master/js/jquery.isotope.min.js') }}"></script>
<script src="{{ asset('master/js/main.js?ver=3') }}"></script>
<script src="{{ asset('master/js/wow.min.js') }}"></script>
<script src="{{ asset('master/owl/owl.carousel.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')}
        });
    });
</script>

@if (session('register_not_allowed'))
    <script>
        $(function(){
            $("#registerNotAllowed").modal('show');
        });
    </script>
@endif
<script>
    $(document).ready(function () {
        // var owl = $('.owl-carousel');
        // (Leasing Management Software) carousel slider
        $('.owl-carousel.one').owlCarousel({
            margin: 10,
            nav: true,
            loop: true,
            autoplay: true,
            autoplayTimeout: 2000,
            autoplayHoverPause: true,
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

        // (The Team Behind the Leasing Software) carousel slider
        $('.owl-carousel.two').owlCarousel({
            margin: 10,
            nav: true,
            loop: true,
            autoplay: false,
            autoplayTimeout: 2000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        })
    })
</script>
<!-- for jquery count function -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.count').each(function () {
            $(this).prop('Counter', 0).animate({
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


<!-- forn on load modal show -->
<script type="text/javascript">


    function popup_trigger() {
        if (getCookie("hcp") == '') {
            $('#page_cookies_modal').modal('show');
        }
    }

    $(window).load(function () {
        popup_trigger();
    });

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
</script>

<script>
    $(document).ready(function () {
        $('.btn_disagree').click(function () {
            setCookie("hcp", 1, 1);
            $('#page_cookies_modal').modal('hide');
        });

        $('.btn_agree').click(function () {
            setCookie("hcp", 1, 1);
            $('#page_cookies_modal').modal('hide');
        });
    });
</script>
<script>
    // $(document).ready(function () {
    //
    //     $('#splan').change(function () {
    //         // calplan();
    //     });
    //
    //     $('#syear').change(function () {
    //         // calplan();
    //     });
    // });
    //
    // function calplan() {
    //
    //     var splan = $('#splan option:selected').data('price');
    //     var syear = $('#syear').val();
    //     var gvofs = parseInt('0');
    //     var anyoffer = '--';
    //
    //     var annual_discount = parseInt($('#splan option:selected').data('annaual_discount'));
    //
    //     if ((splan != '') && (syear != '')) {
    //         gvofs = parseInt(parseInt(splan) * parseInt(syear));
    //
    //         if (syear == '24') {
    //             anyoffer = '10%';
    //             $('#net_payable').html(Math.round(gvofs - gvofs * (annual_discount  *  1) / 100));
    //         } else if (syear == '36') {
    //             anyoffer = '20%';
    //             $('#net_payable').html(Math.round(gvofs - gvofs * (annual_discount  *  2) / 100));
    //         } else {
    //             $('#net_payable').html(gvofs);
    //         }
    //     }
    //
    //     $('#anyoffer').html(anyoffer);
    //     $('#gvofs').html(Math.round(gvofs));
    //
    //     //change the discount applicable
    //     if(annual_discount){
    //         $('#annual_discount_for_selected').text(annual_discount);
    //     } else {
    //         $('#annual_discount_for_selected').text("10");
    //     }
    // }

</script>
@include('layouts._master_popups')
@yield('footer-script')
<script>
    $('#getInTouchWithUs').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("getintouchwithus") }}',
            type: 'post',
            dataType: 'json',
            data: $('#getInTouchWithUs').serialize(),
            beforeSend: function () {
                $('.getintouchsuccess').html('').hide();
                $('#getInTouchWithUs .error').remove();
            },
            success: function (response) {
                if (response['status']) {
                    //show the success message to the users...
                    $('.getintouchsuccess').html(response.message).show();
                    $('#getInTouchWithUs')[0].reset();
                } else {
                    var errors = response.errorMessages;
                    $.each(errors, function (i, e) {
                        $('#getInTouchWithUs input[name="' + i + '"]').after('<span class="error">' + e.join('1') + '</span>');
                    });
                }
            }
        });
    });
</script>

<script>
    $('#proceed_subscription_plan').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            data : $(this).serialize(),
            type : 'post',
            dataType : 'json',
            beforeSend: function(){
              $('.error').remove();
            },
            success : function(response){
                if(response['status']){
                    window.location.href = response.redirect_link;
                } else {
                    var errors = response.errors;
                    $.each(errors, function(i, e){
                        console.log(i + e);
                        $("select[name='"+i+"'] , input[name='"+i+"']").after('<span class="error">'+e+'</span>');
                    });
                }
            }
        })
    });

    $('#buildYourPlan_Form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            data : $(this).serialize(),
            type : 'post',
            dataType : 'json',
            beforeSend: function(){
                $('.buildyourplan_success').html('').hide();
                $('.error').remove();
            },
            success : function(response){
                if(response['status']){
                    $('.buildyourplan_success').html(response.message).show();
                    $('#buildYourPlan_Form')[0].reset();
                } else {
                    var errors = response.errors;
                    $.each(errors, function(i, e){
                        $("select[name='"+i+"'] , input[name='"+i+"']").after('<span class="error">'+e+'</span>');
                    });
                }
            }
        })
    });

    $('.apply_coupon_code').on('click', function(){
        //check for the value inside the coupon code input field if that is blank then in that case show the error on the popup..
        var copoun_code = $('input[name="coupon_code"]').val().trim();
        $('.error').remove();
        if(copoun_code == ""){
            $("input[name='coupon_code']").after('<span class="error">Please type a valid coupon code.</span>');
        } else {
            calculateCart();
        }
        return false;

    });

    $('#syear, #splan').on('change', function(){
        calculateCart();
    });

    function calculateCart(removeErrors = true){
        $.ajax({
            url : '{{ route('master.pricing.calccart') }}',
            dataType : 'json',
            data : $('#proceed_subscription_plan').serialize(),
            beforeSend: function(){
                if(removeErrors){
                    $('.error').remove();
                }
            },
            type : 'post',
            success : function(response){
                if(response['status']){
                    $('#net_payable').html(response.net_payable);
                    $('#coupon_discount').html('$ '+response.coupon_discount);
                    $('.coupon_code_discount_row').show();
                    $('#anyoffer').html(response.offer+" %");
                    $('#gvofs').html(response.gross_value);
                } else {
                    if(typeof (response.errors)!="undefined"){
                        $('input[name="coupon_code"]').val('');
                        var errors = response.errors;
                        $.each(errors, function(i, e){
                            $("select[name='"+i+"'] , input[name='"+i+"']").after('<span class="error">'+e+'</span>');
                        });
                    } else if(typeof(response.errorMessage)!="undefined"){
                        $("input[name='coupon_code']").after('<span class="error">'+response.errorMessage+'</span>');
                        $('input[name="coupon_code"]').val('');
                        calculateCart(false);
                    }
                }
            }
        })
    }

    $(function () {
        $('#pricing_Modal').on('hidden.bs.modal', function () {
            // do something…
            $('#proceed_subscription_plan')[0].reset();
            $('.error').hide();
            $('#anyoffer').html('--');
            $('#gvofs').html('0');
            $('#coupon_discount').html('$ 0');
            $('.coupon_code_discount_row').hide();
            $('#net_payable').html('0');
        })
    })

</script>

</body>
</html>