<form method="post" action="" enctype="multipart/form-data">
    {{ @csrf_field() }}

    <div class="row">
        <div class="col-lg-9 col-xl-9">

            <div class="form-row">

                <div class="form-group col-md-12">
                    <label for="country">Country (required)</label>
                    <select id="country"
                            class="form-control @if($errors->has('country')) is-invalid @endif"
                            name="country" autocomplete="off">
                        <option value="">--Select Country--</option>
                        @foreach($countries as $country)
                            <option  data-id="{{ $country->id }}" value="{{ $country->name }}"
                                     @if(old('country', $user->country) == $country->name) selected="selected" @endif>{{ $country->name }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('country'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('country') }}</strong>
                        </div>
                    @endif
                </div>



            </div>

            <div class="row  @if(old('country') != 'India') hidden @endif country_selected">
                <div class="form-group {{ $errors->has('state') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                    <select id="state" class="form-control" name="state">
                        <option value="">--Select State--</option>
                        @foreach($states as $state)
                            <option value="{{ $state->state_name }}" @if($user->state == $state->state_name)  selected="selected" @endif>{{ $state->state_name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('state'))
                        <span class="help-block">
                                                <strong>{{ $errors->first('state') }}</strong>
                                            </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('gstin') ? ' has-error' : '' }} col-md-6 col-sm-12 required">
                    <input type="text" class="form-control" id="gstin" value="{{ old('gstin', $user->gstin) }}" name="gstin" placeholder="GSTIN*">
                    @if ($errors->has('gstin'))
                        <span class="help-block">
                                                <strong>{{ $errors->first('gstin') }}</strong>
                                            </span>
                    @endif
                </div>

            </div>


            <div class="form-row">

                <div class="form-group col-md-12">
                    <label for="applicable_gaap">Primary Applicable GAAPs (required)</label>
                    <select id="applicable_gaap"
                            class="form-control @if($errors->has('applicable_gaap')) is-invalid @endif"
                            name="applicable_gaap" autocomplete="off">
                        @foreach($accounting_standards as $standard)
                            <option value="{{ $standard->id }}" @if($standard->id == old('applicable_gaap', $user->applicable_gaap)) selected="selected" @endif>{{ $standard->title }}</option>
                        @endforeach

                    </select>

                    @if ($errors->has('applicable_gaap'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('applicable_gaap') }}</strong>
                        </div>
                    @endif
                </div>



            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="legal_entity_name">Legal Entity Name (required)</label>
                    <input id="legal_entity_name" type="text"
                           class="form-control @if($errors->has('legal_entity_name')) is-invalid @endif"
                           name="legal_entity_name"
                           value="{{ old('legal_entity_name',$user->legal_entity_name) }}"
                           autocomplete="off">

                    @if ($errors->has('legal_entity_name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('legal_entity_name') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="authorised_person_name">Authorised Person Name
                        (required)</label>
                    <input id="authorised_person_name" type="text"
                           class="form-control @if($errors->has('authorised_person_name')) is-invalid @endif"
                           name="authorised_person_name"
                           value="{{ old('authorised_person_name',$user->authorised_person_name) }}"
                           autocomplete="off">

                    @if ($errors->has('authorised_person_name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('authorised_person_name') }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="authorised_person_dob">Authorised Person Date Of Birth
                        (required)</label>
                    <input id="authorised_person_dob" type="text"
                           class="form-control @if($errors->has('authorised_person_dob')) is-invalid @endif"
                           name="authorised_person_dob"
                           value="{{ old('authorised_person_dob',date('Y-m-d', strtotime($user->dob))) }}"
                           autocomplete="off">

                    @if ($errors->has('authorised_person_dob'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('authorised_person_dob') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="gender">Gender (required)</label>
                    <select name="gender"
                            class="form-control @if($errors->has('gender')) is-invalid @endif"
                            autocomplete="off">
                        <option value="">--Select Gender--</option>
                        <option value="1"
                                @if(old('gender',$user->gender) == '1') selected="selected" @endif>
                            Male
                        </option>
                        <option value="2"
                                @if(old('gender',$user->gender) == '2') selected="selected" @endif >
                            Female
                        </option>
                        <option value="3" @if("3" == old('gender', $user->gender)) selected="selected" @endif>Don't want to disclose</option>
                    </select>

                    @if ($errors->has('gender'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('gender') }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="authorised_person_designation">Authorised Person Designation
                        (required)</label>
                    <input id="authorised_person_designation" type="text"
                           class="form-control @if($errors->has('authorised_person_designation')) is-invalid @endif"
                           name="authorised_person_designation"
                           value="{{ old('authorised_person_designation',$user->authorised_person_designation) }}"
                           autocomplete="off">

                    @if ($errors->has('authorised_person_designation'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('authorised_person_designation') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="email">
                        E-Mail Address (required)</label>
                    <input id="email" type="email"
                           class="form-control @if($errors->has('email')) is-invalid @endif"
                           name="email" value="{{ old('email',$user->email) }}"
                           autocomplete="off">

                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="username">Login ID (required)</label>
                    <input id="username" type="text"
                           class="form-control @if($errors->has('username')) is-invalid @endif"
                           name="username" value="{{ old('username',$user->username) }}"
                           autocomplete="off">

                    @if ($errors->has('username'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('username') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="password">Password (required)</label>
                    <input id="password" type="password" class="form-control @if($errors->has('password')) is-invalid @endif" name="password"
                           autocomplete="off">

                    @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="password-confirm">Confirm Password (required)</label>
                    <input id="password-confirm" type="password" class="form-control"
                           name="password_confirmation" autocomplete="off">
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Submit</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-danger">Cancel</a>
        </div>
    </div>
</form>