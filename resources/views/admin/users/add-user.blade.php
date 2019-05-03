@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Create User
@endsection

@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link href="{{ asset('assets/plugins/datetimepicker/css/daterangepicker.css') }}" rel="stylesheet"/>
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb-holder">
                    <h1 class="main-title float-left">Add User</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Add User</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- end row -->

        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">

                <p>{{ session()->get('success') }}</p>
            </div>
        @endif

        @if ($errors->has('profile_pic'))
            <div class="alert alert-danger" role="alert">
                <ul>
                    <li> {{ $errors->first('profile_pic') }}</li>
                </ul>
            </div>
        @endif

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

                <div class="card mb-3">

                    <div class="card-header">
                        <h3><i class="fa fa-user-o"></i> Add User</h3>
                        User can be Added from here.
                    </div>


                    <div class="card-body">

                        @include('admin.users._form')

                    </div>
                </div><!-- end card-->
            </div>

        </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script>
        $(function () {
            $('input[name="authorised_person_dob"]').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:-18",
                dateFormat: "yy-mm-dd"
            });
        });

        $(document).ready(function () {

            $('#country').on('change', function(){
                fetchStates();
            });

            fetchStates();

            function fetchStates(){
                var selected = "";
                $.ajax({
                    url : '/fetch-states/'+$('#country option:selected').data('id'),
                    dataType : 'json',
                    beforeSend: function(){
                        $('#state').html('');
                        $('#state').html('<option value="">--Select State--</option>');
                    },
                    success : function (response) {
                        if(response.states.length){
                            $.each(response.states, function(i,e){

                                if('{!! old('state') !!}' == e.state_name){
                                    $('#state')
                                        .append($("<option></option>")
                                            .attr("value",e.state_name)
                                            .attr('selected', 'selected')
                                            .text(e.state_name));
                                } else {
                                    $('#state')
                                        .append($("<option></option>")
                                            .attr("value",e.state_name)
                                            .text(e.state_name));
                                }


                            });
                            $('.country_selected').show();
                        } else {
                            $('#state').val('');
                            $('.country_selected').hide();
                        }
                    }
                })
            }
        });
    </script>
@endsection