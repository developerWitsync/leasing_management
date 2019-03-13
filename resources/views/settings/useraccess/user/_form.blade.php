<form class="form-horizontal" method="POST" action="">
    <div class="categoriesOuter leasedetOuter clearfix">
        {{ csrf_field() }}
        <div class="form-group{{ $errors->has('authorised_person_name') ? ' has-error' : '' }} required">
            <label for="authorised_person_name" class="col-md-12 control-label">Authorised Person
                Name</label>

            <div class="col-md-12">
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
            <label for="authorised_person_dob" class="col-md-12 control-label">Authorised Person
                Date Of Birth</label>

            <div class="col-md-12">
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
            <label for="gender" class="col-md-12 control-label">Gender</label>

            <div class="col-md-12">
                <select name="gender" class="form-control">
                    <option value="">--Select Gender--</option>
                    <option value="1" @if("1" == old('gender', $user->gender)) selected="selected" @endif>
                        Male
                    </option>
                    <option value="2" @if("2" == old('gender', $user->gender)) selected="selected" @endif>
                        Female
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
            <label for="authorised_person_designation" class="col-md-12 control-label">Authorised
                Person Designation</label>

            <div class="col-md-12">
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
            <label for="email" class="col-md-12 control-label">Authorised Person E-Mail
                Address</label>

            <div class="col-md-12">
                <input id="email" type="email" class="form-control" name="email"
                       value="{{ old('email', $user->email) }}">

                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }} required">
            <label for="username" class="col-md-12 control-label">Login ID</label>

            <div class="col-md-12">
                <input id="username" type="text" class="form-control" name="username"
                       value="{{ old('username', $user->username) }}">

                @if ($errors->has('username'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} required">
            <label for="password" class="col-md-12 control-label">Password</label>

            <div class="col-md-12">
                <input id="password" type="password" class="form-control" name="password">

                @if ($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group required">
            <label for="password-confirm" class="col-md-12 control-label">Confirm
                Password</label>
            <div class="col-md-12">
                <input id="password-confirm" type="password" class="form-control"
                       name="password_confirmation">
            </div>
        </div>

        <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }} required">
            <label for="password" class="col-md-12 control-label">Select Role</label>

            <div class="col-md-12">
                <select name="role" class="form-control">
                    <option value="">-- Select Role --</option>
                    @foreach($roles as $role)
                        <option value="{{$role->id}}" @if(in_array($role->id, $assignedRole) || old('role') == $role->id) selected="selected" @endif>{{ $role->display_name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('role'))
                    <span class="help-block">
                        <strong>{{ $errors->first('role') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    </div>

    <div class="form-group  btnMainBx">
        <div class="col-md-4 col-sm-4 btn-backnextBx">
            <a href="{{ route('settings.useraccess') }}" class="btn btn-danger">Cancel</a>
        </div>

        <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">
            &nbsp;
        </div>

        <div class="col-md-4 col-sm-4 btn-backnextBx rightlign">
            <button type="submit" class="btn btn-primary next_submit">
                Submit
            </button>
        </div>
    </div>

</form>
