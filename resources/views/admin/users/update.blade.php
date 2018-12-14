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
                                            <label for="security_question">Security Question (required)</label>
                                            <select name="security_question" id="security_question" class="form-control @if($errors->has('security_question')) is-invalid @endif">
                                                <option value="">Select Security Question</option>
                                                @foreach($questions as $question)
                                                    <option value="{{ $question->id }}" @if(old('security_question', $user->security_question) == $question->id) selected="selected" @endif>{{ $question->question }}</option>
                                                @endforeach

                                            </select>
                                            @if($errors->has('security_question'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('security_question') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="security_answer">Security Answer(required)</label>
                                            <input type="text" class="form-control @if($errors->has('security_answer')) is-invalid @endif" value="{{ old('security_answer', $user->security_answer) }}" name="security_answer" id="security_answer" placeholder="Answer for the selected question" autocomplete="off">
                                            @if($errors->has('security_answer'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('security_answer') }}
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

                                    <div id="avatar_image">
                                        @if($user->profile_pic)
                                            <img alt="image" style="max-width:100px; height:auto;" src="{{ asset("user/{$user->id}/profile_pic/thumbnail/{$user->profile_pic}") }}" />
                                        @else
                                            <img alt="image" style="max-width:100px; height:auto;" src="{{ asset('assets/images/avatars/avatar1.png') }}" />
                                        @endif
                                        <br />
                                        @if($user->profile_pic)
                                                {{--<i class="fa fa-trash-o fa-fw"></i>--}}
                                                {{--<a class="delete_image" href="#">Remove avatar</a>--}}
                                        @endif
                                    </div>

                                    <div id="image_deleted_text"></div>


                                    <div class="m-b-10"></div>

                                    <div class="form-group">
                                        <label>Change avatar</label>
                                        <input type="file" name="profile_pic" class="form-control">
                                    </div>
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

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_AUTOCOMPLETE_KEY') }}&libraries=places&callback=initAutocomplete"
            async defer></script>
    <script>
        // This example displays an address form, using the autocomplete feature
        // of the Google Places API to help users fill in the information.

        // This example requires the Places library. Include the libraries=places
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

        var placeSearch, autocomplete;
        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'
        };

        var elementsMapping = {
            street_number: 'address',
            route: 'address',
            locality: 'city',
            administrative_area_level_1: 'state',
            country: 'country',
            postal_code: 'postal_code'
        };

        function initAutocomplete() {
            // Create the autocomplete object, restricting the search to geographical
            // location types.
            autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById('address')),
                {types: ['geocode']});

            // When the user selects an address from the dropdown, populate the address
            // fields in the form.
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();

            // Get each component of the address from the place details
            // and fill the corresponding field on the form.
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    document.getElementById(elementsMapping[addressType]).value = val;
                }
            }
        }

        // Bias the autocomplete object to the user's geographical location,
        // as supplied by the browser's 'navigator.geolocation' object.
        function geolocate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var geolocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });
                    autocomplete.setBounds(circle.getBounds());
                });
            }
        }
    </script>

@endsection