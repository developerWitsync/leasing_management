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
                            <form class="form-horizontal" method="POST" action="{{ route('settings.profile.index') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @if($user->parent_id == 0)
                                    <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }} required">
                                        <label for="country" class="col-md-4 control-label">Country Of
                                            Incorporation</label>

                                        <div class="col-md-6">
                                            <select id="country" class="form-control" name="country">
                                                <option value="">--Select Country Of Incorporation--</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->name }}"
                                                            @if($country->name == old('country', $user->country)) selected="selected"
                                                            @endif data-id="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('country'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('date_of_incorporation') ? ' has-error' : '' }} required">
                                        <label for="date_of_incorporation" class="col-md-4 control-label">Date Of
                                            Incorporation</label>

                                        <div class="col-md-6">
                                            <input id="date_of_incorporation" type="text" class="form-control"
                                                   name="date_of_incorporation"
                                                   value="{{ old('date_of_incorporation', \Carbon\Carbon::parse($user->date_of_incorporation)->format(config('settings.date_format'))) }}">

                                            @if ($errors->has('date_of_incorporation'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('date_of_incorporation') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                @endif

                                @if($user->parent_id == 0 && $user->country == "India")
                                    <div class="state_div form-group{{ $errors->has('state') ? ' has-error' : '' }} required">
                                        <label for="state" class="col-md-4 control-label">State</label>
                                        <div class="col-md-6">
                                            <select id="state" class="form-control" name="state">
                                                <option value="">--Select State--</option>
                                                @foreach($states as $state)
                                                    <option value="{{ $state->state_name }}"
                                                            @if($state->state_name == old('state', $user->state)) selected="selected"
                                                            @endif>{{ $state->state_name }}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('state'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="state_div form-group{{ $errors->has('gstin') ? ' has-error' : '' }} required">
                                        <label for="gstin" class="col-md-4 control-label">GSTIN</label>
                                        <div class="col-md-6">
                                            <input id="gstin" type="text" class="form-control"
                                                   name="gstin"
                                                   value="{{ old('gstin', $user->gstin) }}"
                                                   autofocus>

                                            @if ($errors->has('gstin'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('gstin') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>

                                @endif


                                @if($user->parent_id == 0)
                                    <div class="form-group{{ $errors->has('legal_entity_name') ? ' has-error' : '' }} required">
                                        <label for="legal_entity_name" class="col-md-4 control-label">Legal Entity
                                            Name</label>

                                        <div class="col-md-6">
                                            <input id="legal_entity_name" type="text" class="form-control"
                                                   name="legal_entity_name"
                                                   value="{{ old('legal_entity_name', $user->legal_entity_name) }}"
                                                   autofocus>

                                            @if ($errors->has('legal_entity_name'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('legal_entity_name') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }} required">
                                        <label for="address" class="col-md-4 control-label">Registered Address</label>

                                        <div class="col-md-6">
                                            <input id="address" type="text" class="form-control"
                                                   name="address"
                                                   value="{{ old('address', $user->address) }}"
                                                   autofocus>

                                            @if ($errors->has('address'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                @endif

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

                                {{--<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }} required">--}}
                                    {{--<label for="username" class="col-md-4 control-label">Login ID</label>--}}

                                    {{--<div class="col-md-6">--}}
                                        {{--<input id="username" type="text" class="form-control" name="username"--}}
                                               {{--value="{{ old('username',$user->username) }}">--}}

                                        {{--@if ($errors->has('username'))--}}
                                            {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('username') }}</strong>--}}
                                    {{--</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}

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

                                @if($user->parent_id == 0)
                                    <div class="form-group{{ $errors->has('certificates') ? ' has-error' : '' }} ">
                                        <label for="file" class="col-md-4 control-label"> Commercial license copy</label>
                                        <div class="col-md-6 frmattachFile" style="width: auto;">
                                            <input type="name" id="upload" name="name" class="form-control"
                                                   disabled="disabled">
                                            <button type="button" class="browseBtn">Browse</button>
                                            <input type="file" id="file-name" name="certificates" class="fileType">
                                            <h6 class="disabled">{{ config('settings.file_size_limits.file_validation') }}</h6>
                                            @if ($errors->has('file'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('certificates') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        @if($user->certificates !='')
                                            <a href="{{asset('uploads/'.$user->certificates)}}" class="downloadIcon"
                                               target="_blank"><i class="fa fa-download"></i></a>
                                        @endif
                                    </div>
                                @endif

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

            $('#date_of_incorporation').datepicker({
                dateFormat: "dd-M-yy",
                changeMonth: true,
                changeYear: true
            });

            $('input[name="authorised_person_dob"]').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:-18"
            });

            $('#country').on('change', function(){
                if($(this).val() == "India"){
                    $('.state_div').show();
                } else {
                    $('.state_div').hide();
                    $('#state').val('');
                    $('#gstin').val('');
                }
            });

            $('#file-name').change(function () {
                $('#file-name').show();
                var filename = $('#file-name').val();
                var or_name = filename.split("\\");
                $('#upload').val(or_name[or_name.length - 1]);
            });

        });

    </script>
@endsection