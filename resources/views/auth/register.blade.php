@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create a Business Account</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }} required">
                            <label for="country" class="col-md-4 control-label">Country Of Incorporation</label>

                            <div class="col-md-6">
                                <select id="country" class="form-control" name="country">
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
                        </div>

                        <div class="form-group{{ $errors->has('legal_status') ? ' has-error' : '' }} required">
                            <label for="legal_status" class="col-md-4 control-label">Legal Status</label>

                            <div class="col-md-6">
                                <select id="legal_status" class="form-control" name="legal_status">
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

                        <div class="form-group{{ $errors->has('applicable_gaap') ? ' has-error' : '' }} required">
                            <label for="applicable_gaap" class="col-md-4 control-label">Primary Applicable GAAPs</label>

                            <div class="col-md-6">
                                <select id="applicable_gaap" class="form-control" name="applicable_gaap">
                                    <option value="">--Select Primary Applicable GAAP--</option>
                                    <option value="Ministry Of Corporate Affairs (MCA)" @if("Ministry Of Corporate Affairs (MCA)" == old('applicable_gaap')) selected="selected" @endif>Ministry Of Corporate Affairs (MCA)</option>
                                </select>

                                @if ($errors->has('applicable_gaap'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('applicable_gaap') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('industry_type') ? ' has-error' : '' }} required">
                            <label for="industry_type" class="col-md-4 control-label">Industry Type</label>

                            <div class="col-md-6">
                                <select id="industry_type" class="form-control" name="industry_type">
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

                        <div class="form-group{{ $errors->has('legal_entity_name') ? ' has-error' : '' }} required">
                            <label for="legal_entity_name" class="col-md-4 control-label">Legal Entity Name</label>

                            <div class="col-md-6">
                                <input id="legal_entity_name" type="text" class="form-control" name="legal_entity_name" value="{{ old('legal_entity_name') }}" autofocus>

                                @if ($errors->has('legal_entity_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('legal_entity_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('authorised_person_name') ? ' has-error' : '' }} required">
                            <label for="authorised_person_name" class="col-md-4 control-label">Authorised Person Name</label>

                            <div class="col-md-6">
                                <input id="authorised_person_name" type="text" class="form-control" name="authorised_person_name" value="{{ old('authorised_person_name') }}" autofocus>

                                @if ($errors->has('authorised_person_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('authorised_person_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('authorised_person_dob') ? ' has-error' : '' }} required">
                            <label for="authorised_person_dob" class="col-md-4 control-label">Authorised Person Date Of Birth</label>

                            <div class="col-md-6">
                                <input id="authorised_person_dob" type="text" class="form-control" name="authorised_person_dob" value="{{ old('authorised_person_dob') }}" autofocus>

                                @if ($errors->has('authorised_person_dob'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('authorised_person_dob') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }} required">
                            <label for="gender" class="col-md-4 control-label">Gender</label>

                            <div class="col-md-6">
                                <select name="gender" class="form-control">
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

                        <div class="form-group{{ $errors->has('authorised_person_designation') ? ' has-error' : '' }} required">
                            <label for="authorised_person_designation" class="col-md-4 control-label">Authorised Person Designation</label>

                            <div class="col-md-6">
                                <input id="authorised_person_designation" type="text" class="form-control" name="authorised_person_designation" value="{{ old('authorised_person_designation') }}" autofocus>

                                @if ($errors->has('authorised_person_designation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('authorised_person_designation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} required">
                            <label for="email" class="col-md-4 control-label">Authorised Person E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }} required">
                            <label for="username" class="col-md-4 control-label">Login ID</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}">

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} required">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group required">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }} required">
                            <label for="phone" class="col-md-4 control-label">Mobile Number</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('annual_reporting_period') ? ' has-error' : '' }} required">
                            <label for="annual_reporting_period" class="col-md-4 control-label">Annual Reporting Period</label>

                            <div class="col-md-6">
                                <input id="annual_reporting_period" type="text" class="form-control" name="annual_reporting_period">

                                @if ($errors->has('annual_reporting_period'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('annual_reporting_period') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} required">
                            <label for="currency" class="col-md-4 control-label">Reporting Currency</label>

                            <div class="col-md-6">
                                <select id="currency" class="form-control" name="currency">
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

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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