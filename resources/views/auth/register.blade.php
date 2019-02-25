@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
    <style>
        .form-group label {
            font-weight: 600;
            font-size: 14px;
            line-height: 19px;
            vertical-align: top;
        }
    </style>
@endsection
@section('content')
    <div class="container register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="{{ asset('images/rocket.png') }}" alt=""/>
                <h3>Welcome</h3>
                <p>You are few minutes away from getting started!</p>
                <a class="login_register" href="{{ route('login') }}">Login</a>
            </div>
            <div class="col-md-9 register-right">
                <div class="tab-content">
                    <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                        <div class="loginLogo">
                            <a href="/">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
                            </a>
                        </div>

                        <h3 class="register-heading">Register your Business</h3>
                        <div class="row register-form">
                            <form class="" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="selected_plan" value="{{ $package->id }}">
                                <div class="row">
                                    <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }} col-md-12 col-sm-12 required">
                                        <select id="country" class="form-control" name="country">
                                            <option value="">--Select Country Of Incorporation--</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->name }}" @if($country->name == old('country')) selected="selected" @endif data-id="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('country'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('country') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="row  @if(old('country') != 97) hidden @endif country_selected">
                                    <div class="form-group {{ $errors->has('state') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <select id="state" class="form-control" name="state">
                                            <option value="">--Select State--</option>

                                        </select>
                                        @if ($errors->has('state'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('state') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('gstin') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <input type="text" class="form-control" id="gstin" value="{{ old('gstin') }}" name="gstin" placeholder="GSTIN*">
                                        @if ($errors->has('gstin'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('gstin') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="row ">
                                    <div class="form-group {{ $errors->has('legal_entity_name') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <input id="legal_entity_name" type="text" class="form-control" name="legal_entity_name" value="{{ old('legal_entity_name') }}" placeholder="Legal Entity Name*">
                                        @if ($errors->has('legal_entity_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('legal_entity_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="Registered Address*">
                                        @if ($errors->has('address'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="form-group {{ $errors->has('applicable_gaap') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <select id="applicable_gaap" class="form-control" name="applicable_gaap">
                                            <option value="">--Select Primary Applicable GAAP--</option>
                                            <option value="IFRS- 16 (International Financial Reporting Standard)" @if("IFRS- 16 (International Financial Reporting Standard)" == old('applicable_gaap')) selected="selected" @endif>IFRS- 16 (International Financial Reporting Standard)</option>
                                            <option value="IND-AS-116 (Indian Accounting Standard)" @if("IND-AS-116 (Indian Accounting Standard)" == old('applicable_gaap')) selected="selected" @endif>IND-AS-116 (Indian Accounting Standard)</option>
                                            <option value="SFRS(I)16 - (Singapore Financial Reporting Standard (International))" @if("SFRS(I)16 - (Singapore Financial Reporting Standard (International))" == old('applicable_gaap')) selected="selected" @endif>SFRS(I)16 - (Singapore Financial Reporting Standard (International))</option>
                                            <option value="MFRS 16 - (Malaysian Financial Reporting Standard)" @if("MFRS 16 - (Malaysian Financial Reporting Standard)" == old('applicable_gaap')) selected="selected" @endif>MFRS 16 - (Malaysian Financial Reporting Standard)</option>
                                        </select>

                                        @if ($errors->has('applicable_gaap'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('applicable_gaap') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('authorised_person_name') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <input id="authorised_person_name" type="text" class="form-control" name="authorised_person_name" value="{{ old('authorised_person_name') }}" placeholder="Authorised Person Name*">
                                        @if ($errors->has('authorised_person_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('authorised_person_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="row ">
                                    <div class="form-group {{ $errors->has('authorised_person_dob') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <input id="authorised_person_dob" type="text" class="form-control" name="authorised_person_dob" value="{{ old('authorised_person_dob') }}" placeholder="Authorised Person Date Of Birth*">
                                        @if ($errors->has('authorised_person_dob'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('authorised_person_dob') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <select name="gender" class="form-control">
                                            <option value="">--Select Gender--</option>
                                            <option value="1" @if("1" == old('gender')) selected="selected" @endif>Male</option>
                                            <option value="2" @if("2" == old('gender')) selected="selected" @endif>Female</option>
                                            <option value="3" @if("3" == old('gender')) selected="selected" @endif>Don't want to disclose</option>
                                        </select>

                                        @if ($errors->has('gender'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('gender') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="row ">
                                    <div class="form-group {{ $errors->has('authorised_person_designation') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <input id="authorised_person_designation" type="text" class="form-control" name="authorised_person_designation" value="{{ old('authorised_person_designation') }}" placeholder="Authorised Person Designation*">
                                        @if ($errors->has('authorised_person_designation'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('authorised_person_designation') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Authorised Person Email*">
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row ">
                                    <div class="form-group {{ $errors->has('admin_rights') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <strong>
                                            Are you going to operate with admin rights?
                                        </strong>
                                        <br>
                                        <span>
                                            <input type="checkbox" class="chb" id="yes" name="admin_rights" value="yes" @if(old('admin_rights') == "yes") checked="checked" @endif>
                                            <label for="yes">Yes</label>
                                        </span>
                                        <span>
                                            <input type="checkbox" class="chb" id="no" name="admin_rights" value="no" @if(old('admin_rights') == "no") checked="checked" @endif>
                                            <label for="no">No</label>
                                        </span>
                                        @if ($errors->has('admin_rights'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('admin_rights') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group {{ $errors->has('certificates') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                                        <label>
                                            {{ ucfirst(strtolower('UPLOAD REGISTRATION CERTIFICATE')) }}
                                        </label>
                                        <div class="frmattachFile" style="position: relative;">
                                            <input type="name" id="upload" name="name" class="form-control" disabled="disabled">

                                            <span style="position: absolute;right: -15px; top: 0px;">
                                                <button type="button" class="browseBtn">Browse</button>
                                                <input type="file" id="file-name" name="certificates" class="fileType">
                                            </span>

                                            <h6 class="disabled">{{ config('settings.file_size_limits.file_validation') }}</h6>
                                        </div>
                                        @if ($errors->has('certificates'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('certificates') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="row termsConditionParent">
                                    <div class="form-group {{ $errors->has('declaration') ? ' has-error' : '' }} col-md-12 col-sm-12 required">
                                        <span>
                                            <input type="checkbox" id="declaration" name="declaration" value="1">
                                            <label for="declaration" class="declarationLabel">
                                                {{ ucfirst(strtolower('PLEASE CONFIRM THAT YOU ARE AUTHORIZED AS PER NORMS OF THE COMPANY POLICIES AND PROCEDURES TO REGISTER YOUR COMPANY FOR USE OF THE WITSYNC LEASING SOFTWARE.')) }}
                                            </label>
                                        </span>

                                        @if ($errors->has('declaration'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('declaration') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                </div>

                                <div class="row termsConditionParent">
                                    <div class="form-group {{ $errors->has('terms_and_condition') ? ' has-error' : '' }} col-md-12 col-sm-12 required">
                                        <span>
                                            <input type="checkbox" id="terms_and_condition" name="terms_and_condition" value="yes">
                                            <label for="terms_and_condition">{{ ucfirst(strtolower('ACCEPT AND AGREE TO TERMS AND POLICY OF WITSYNC')) }}</label>
                                        </span>

                                        @if ($errors->has('terms_and_condition'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('terms_and_condition') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                </div>

                                <div class="form-group col-md-12 col-sm-12  text-center">
                                    <input type="submit" class="btnRegister" value="Register"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            $(".chb").change(function() {
                $(".chb").prop('checked', false);
                $(this).prop('checked', true);

                if($(this).val() == "no"){
                    bootbox.dialog({
                        message: '{{ ucfirst(strtolower('PLEASE ENTER DETAILS OF PERSON WHO IS AUTHORIZED TO CREATE AN ACCOUNT WITH ADMIN RIGHTS. SINCE COMPANY DETAILS ARE SENSITIVE. WE APPRECIATE FOR YOUR UNDERSTANDING. THANK YOU.')) }}',
                        buttons: [
                            {
                                label: "OK",
                                className: "btn btn-success pull-left",
                                callback: function() {

                                }
                            },

                        ]
                    });
                }
            });

            $('input[name="authorised_person_dob"]').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:-18"
            });

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
                            $('.country_selected').removeClass('hidden');
                        } else {
                            $('#state').val('');
                            $('#gstin').val('');
                            $('.country_selected').addClass('hidden');
                        }
                    }
                })
            }

            $('#file-name').change(function () {
                $('#file-name').show();
                var filename = $('#file-name').val();
                var or_name = filename.split("\\");
                $('#upload').val(or_name[or_name.length - 1]);
            });
        });
    </script>
@endsection