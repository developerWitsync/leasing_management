@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Update User
@endsection

@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link href="{{ asset('assets/plugins/datetimepicker/css/daterangepicker.css') }}" rel="stylesheet" />
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

                        <form method="post" action="{{ route('admin.user.add')}}" enctype="multipart/form-data">
                            {{ @csrf_field() }}

                            <div class="row">
                                <div class="col-lg-9 col-xl-9">

                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="country">Country (required)</label>
                                            <select id="country" class="form-control" name="country" autocomplete="off">
                                            <option value="">--Select Country--</option>
                                            @foreach($countries as $country)
                                            <option value="{{ $country->id }}" @if($country->id == old('country')) selected="selected" @endif>{{ $country->name }}</option>
                                            @endforeach
                                            </select>

                                            @if ($errors->has('country'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('country') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                 <div class="form-group col-md-6">
                                     <label for="legal_status">Legal Status (required)</label>
                                           <select id="legal_status" class="form-control" name="legal_status" autocomplete="off">
                                    <option value="">--Select Legal Status--</option>
                                    <option value="1" @if(old('legal_status') == '1') selected="selected" @endif>Legal</option>
                                    <option value="0" @if(old('legal_status') == '0') selected="selected" @endif>Illegal</option>
                                </select>

                                @if ($errors->has('legal_status'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('legal_status') }}</strong>
                                    </span>
                                @endif
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="applicable_gaap">Primary Applicable GAAPs (required)</label>
                                           <select id="applicable_gaap" class="form-control" name="applicable_gaap" autocomplete="off">
                                    <option value="">--Select Primary Applicable GAAP--</option>
                                    <option value="Ministry Of Corporate Affairs (MCA)" @if("Ministry Of Corporate Affairs (MCA)" == old('applicable_gaap')) selected="selected" @endif>Ministry Of Corporate Affairs (MCA)</option>
                                </select>

                                @if ($errors->has('applicable_gaap'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('applicable_gaap') }}</strong>
                                    </span>
                                @endif
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label for="industry_type">Industry Type (required)</label>
                                           <select id="industry_type" class="form-control" name="industry_type" autocomplete="off">
                                    <option value="">--Select Industry Type--</option>
                                    @foreach($industry_types as $type)
                                        <option value="{{ $type->id }}" @if($type->id == old('industry_type')) selected="selected" @endif>{{ $type->title }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('industry_type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('industry_type') }}</strong>
                                    </span>
                                @endif
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="legal_entity_name">legal_entity_name (required)</label>
                                            <input id="legal_entity_name" type="text" class="form-control" name="legal_entity_name" value="{{ old('legal_entity_name') }}" autocomplete="off">

                                @if ($errors->has('legal_entity_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('legal_entity_name') }}</strong>
                                    </span>
                                @endif
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="authorised_person_name">Authorised Person Name (required)</label>
                                           <input id="authorised_person_name" type="text" class="form-control" name="authorised_person_name" value="{{ old('authorised_person_name') }}" autocomplete="off">

                                @if ($errors->has('authorised_person_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('authorised_person_name') }}</strong>
                                    </span>
                                @endif
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="authorised_person_dob">Authorised Person Date Of Birth (required)</label>
                                            <input id="authorised_person_dob" type="text" class="form-control" name="authorised_person_dob" value="{{ old('authorised_person_dob') }}" autocomplete="off">

                                @if ($errors->has('authorised_person_dob'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('authorised_person_dob') }}</strong>
                                    </span>
                                @endif
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="gender">Gender (required)</label>
                                            <select name="gender" class="form-control" autocomplete="off">
                                    <option value="">--Select Gender--</option>
                                    <option value="1" @if("1" == old('gender')) selected="selected" @endif>Male</option>
                                    <option value="2" @if("2" == old('gender')) selected="selected" @endif>Female</option>
                                </select>

                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="authorised_person_designation">Authorised Person Designation (required)</label>
                                            <input id="authorised_person_designation" type="text" class="form-control" name="authorised_person_designation" value="{{ old('authorised_person_designation') }}" autocomplete="off">

                                @if ($errors->has('authorised_person_designation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('authorised_person_designation') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    <div class="form-group col-md-6">
                                            <label for="email" >
                           E-Mail Address (required)</label>
                                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autocomplete="off">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="username">Login ID (required)</label>
                                            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" autocomplete="off">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="password">Password (required)</label>
                                           <input id="password" type="password" class="form-control" name="password" autocomplete="off">

                                            @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="password-confirm">Confirm Password (required)</label>
                                              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="phone">Mobile Number (required)</label>
                                             <input id="phone" type="text" class="form-control" name="phone" autocomplete="off">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="annual_reporting_period">Annual Reporting Period (required)</label>
                                           <input id="annual_reporting_period" type="text" class="form-control" name="annual_reporting_period" autocomplete="off">

                                @if ($errors->has('annual_reporting_period'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('annual_reporting_period') }}</strong>
                                    </span>
                                @endif
                                        </div>

                                <div class="form-group col-md-6">
                                    <label for="currency">Reporting Currency(required)</label>
                                   <select id="currency" class="form-control" name="currency" autocomplete="off">
                                    <option value="">--Select Reporting Currency--</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" @if($currency->id == old('currency')) selected="selected" @endif>{{ $currency->code }}  {{ $currency->symbol }}</option>
                                    @endforeach
                                </select>
                                 @if ($errors->has('currency'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('currency') }}</strong>
                                    </span>
                                @endif
                                </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-danger">Cancel</a>

                                </div>


                            </div>

                        </form>

                    </div>
                </div><!-- end card-->
            </div>

        </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script>
        $(function() {
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