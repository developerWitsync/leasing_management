@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        {{--<div class="panel-heading">My Profile</div>--}}

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @include('settings._menubar')
            <div class="">
                <div role="tabpanel" class="tab-pane active">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Edit Profile
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" method="POST" action="{{ route('settings.profile.index') }}">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('authorised_person_name') ? ' has-error' : '' }} required">
                                    <label for="authorised_person_name" class="col-md-4 control-label">Authorised Person
                                        Name</label>

                                    <div class="col-md-6">
                                        <input id="authorised_person_name" type="text" class="form-control"
                                               name="authorised_person_name"
                                               value="{{ old('authorised_person_name', $user->authorised_person_name) }}"
                                               autofocus>

                                        @if ($errors->has('authorised_person_name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('authorised_person_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('authorised_person_dob') ? ' has-error' : '' }} required">
                                    <label for="authorised_person_dob" class="col-md-4 control-label">Authorised Person
                                        Date Of Birth</label>

                                    <div class="col-md-6">
                                        <input id="authorised_person_dob" type="text" class="form-control"
                                               name="authorised_person_dob"
                                               value="{{ old('authorised_person_dob', $user->authorised_person_dob) }}"
                                               autofocus>

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
                                            <option value="1"
                                                    @if("1" == old('gender', $user->gender)) selected="selected" @endif>
                                                Male
                                            </option>
                                            <option value="2"
                                                    @if("2" == old('gender', $user->gender)) selected="selected" @endif>
                                                Female
                                            </option>
                                            <option value="3"
                                                    @if("3" == old('gender', $user->gender)) selected="selected" @endif>
                                                Don't want to disclose
                                            </option>
                                        </select>

                                        @if ($errors->has('gender'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('authorised_person_designation') ? ' has-error' : '' }} required">
                                    <label for="authorised_person_designation" class="col-md-4 control-label">Authorised
                                        Person Designation</label>

                                    <div class="col-md-6">
                                        <input id="authorised_person_designation" type="text" class="form-control"
                                               name="authorised_person_designation"
                                               value="{{ old('authorised_person_designation', $user->authorised_person_designation) }}"
                                               autofocus>

                                        @if ($errors->has('authorised_person_designation'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('authorised_person_designation') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} required">
                                    <label for="email" class="col-md-4 control-label">Authorised Person E-Mail
                                        Address</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email"
                                               value="{{ old('email',$user->email) }}">

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
                                        <input id="username" type="text" class="form-control" name="username"
                                               value="{{ old('username',$user->username) }}">

                                        @if ($errors->has('username'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
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

                                <div class="form-group">
                                    <label for="password-confirm" class="col-md-4 control-label">Confirm
                                        Password</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation">
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }} required">
                                    <label for="phone" class="col-md-4 control-label">Mobile Number</label>

                                    <div class="col-md-6">
                                        <input id="phone" type="text" class="form-control" name="phone"
                                               value="{{ old('phone',$user->phone) }}">

                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-success">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/pages/profile.js') }}"></script>
    <script>
        $(function () {
            $('input[name="authorised_person_dob"]').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:-18"
            });
        });

    </script>
@endsection