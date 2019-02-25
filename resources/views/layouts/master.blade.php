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
        #getInTouchWithUs input{
            /*margin-bottom: 0px;*/
        }
        #getInTouchWithUs .error{
            color: red;
        }
    </style>
    @yield('header-styles')
</head><!--/head-->

<body class="homepage">

    @include('layouts._header_master')

    @yield('content')

    @include('layouts._footer_master')

    <script src="{{ asset('master/js/jquery.js') }}"></script>
    <script src="{{ asset('master/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('master/js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('master/js/jquery.isotope.min.js') }}"></script>
    <script src="{{ asset('master/js/main.js?ver=3') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
            });
        } );
    </script>
    @yield('footer-script')
    <script>
        $('#getInTouchWithUs').on('submit', function(e){
            e.preventDefault();
            $.ajax({
                url : '{{ route("getintouchwithus") }}',
                type : 'post',
                dataType : 'json',
                data : $('#getInTouchWithUs').serialize(),
                beforeSend: function(){
                    $('#getInTouchWithUs .error').remove();
                },
                success : function(response){
                    if(response['status']) {
                        //show the success message to the users...
                        $('.getintouchsuccess').html(response.message).show();
                        $('#getInTouchWithUs')[0].reset();
                    } else {
                        var errors = response.errorMessages;
                        $.each(errors, function(i, e){
                            $('#getInTouchWithUs input[name="'+i+'"]').after('<span class="error">'+e.join('1')+'</span>');
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>