@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Update User
@endsection

@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link href="{{ asset('assets/plugins/datetimepicker/css/daterangepicker.css') }}" rel="stylesheet" />
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb-holder">
                    <h1 class="main-title float-left">Update User</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Update User</li>
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
                        <h3><i class="fa fa-user-o"></i> Update User</h3>
                        User details can be modified from here.
                    </div>


                    <div class="card-body">

                        <form method="post" action="{{ route('admin.manage.user.edit', ['id' => $user->id]) }}" enctype="multipart/form-data">
                            {{ @csrf_field() }}

                            <div class="row">
                                <div class="col-lg-9 col-xl-9">

                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="first_name">First Name (required)</label>
                                            <input type="text" class="form-control @if($errors->has('first_name')) is-invalid @endif" value="{{ old('first_name', $user->first_name) }}" name="first_name" id="first_name" placeholder="First Name" autocomplete="off">
                                            @if($errors->has('first_name'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('first_name') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="last_name">Last Name (required)</label>
                                            <input type="text" class="form-control @if($errors->has('last_name')) is-invalid @endif" value="{{ old('last_name', $user->last_name) }}" name="last_name" id="last_name" placeholder="Last Name" autocomplete="off">
                                            @if($errors->has('last_name'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('last_name') }}
                                                </div>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="email">Email (required)</label>
                                            <input type="email" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{ old('email', $user->email) }}" name="email" id="email" placeholder="Email" autocomplete="off">
                                            @if($errors->has('email'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('email') }}
                                                </div>
                                            @endif
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label for="mobile">Mobile (required)</label>
                                            <input type="text" class="form-control @if($errors->has('mobile')) is-invalid @endif" value="{{ old('mobile', $user->mobile) }}" name="mobile" id="mobile" placeholder="Mobile" autocomplete="off">
                                            @if($errors->has('mobile'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('mobile') }}
                                                </div>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="address">Address (required)</label>
                                            <input type="text" class="form-control @if($errors->has('address')) is-invalid @endif" value="{{ old('address', $user->address) }}" name="address" id="address" placeholder="Address" autocomplete="off" onFocus="geolocate()">
                                            @if($errors->has('address'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('address') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="state">State (required)</label>
                                            <input type="text" class="form-control @if($errors->has('state')) is-invalid @endif" value="{{ old('state', $user->state) }}" name="state" id="state" placeholder="State" autocomplete="off">
                                            @if($errors->has('state'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('state') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="city">City (required)</label>
                                            <input type="text" class="form-control @if($errors->has('city')) is-invalid @endif" value="{{ old('city', $user->city) }}" name="city" id="city" placeholder="City" autocomplete="off">
                                            @if($errors->has('city'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('city') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="state">Postal Code (required)</label>
                                            <input type="text" class="form-control @if($errors->has('postal_code')) is-invalid @endif" value="{{ old('postal_code', $user->postal_code) }}" name="postal_code" id="postal_code" placeholder="Postal Code" autocomplete="off">
                                            @if($errors->has('postal_code'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('postal_code') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="country">Country (required)</label>
                                            <input type="text" class="form-control @if($errors->has('country')) is-invalid @endif" value="{{ old('country', $user->country) }}" name="country" id="country" placeholder="Country" autocomplete="off">
                                            @if($errors->has('country'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('country') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="password">Password (leave empty not to change)</label>
                                            <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif" value="" name="password" id="password" placeholder="Password" autocomplete="off">
                                            @if($errors->has('password'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('password') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="status">Is Verified (required)</label>
                                            <select name="is_verified" id="is_verified" class="form-control @if($errors->has('is_verified')) is-invalid @endif">
                                                <option value="">Select Status</option>
                                                <option value="1" @if(old('status', $user->is_verified) == "1") selected="selected" @endif>Activated</option>
                                                <option value="0" @if(old('status', $user->is_verified) == "0") selected="selected" @endif>Not Activated</option>
                                            </select>
                                            @if($errors->has('is_verified'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('is_verified') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="dob">Date of Birth(required)</label>
                                            <input type="text" class="form-control @if($errors->has('dob')) is-invalid @endif" value="{{ old('dob', date('m/d/Y', strtotime($user->dob))) }}" name="dob" id="dob" placeholder="Date Of Birth" autocomplete="off" readonly="readonly">
                                            @if($errors->has('dob'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('dob') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>


                                    <button class="btn btn-primary" type="submit">Submit</button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-danger">Cancel</a>

                                </div>

                                <div class="col-lg-3 col-xl-3 border-left">
                                    <b>Last Updated date</b>: {{ date('F j, Y H:i a', strtotime($user->updated_at)) }}
                                    <br />
                                    <b>Register date </b>: {{ date('F j, Y H:i a', strtotime($user->created_at)) }}
                                    <br />

                                    <div class="m-b-10"></div>


                                    <div class="m-b-10"></div>

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
    <script src="{{ asset('assets/plugins/datetimepicker/js/daterangepicker.js') }}"></script>
    <script>
        $(function() {
            $('input[name="dob"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
        });
    </script>

@endsection