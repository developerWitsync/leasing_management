@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
     <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Settings | User Access | Assigned Role To User </div>

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
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                     <div class="panel panel-info">
                            <div class="panel-heading">
                            Assigned Role To User
                            
                             </div>
                              <div class="panel-body">
                             <form class="form-horizontal" method="POST">
                            {{ csrf_field() }}
                             
                        <div class="form-group {{ $errors->has('user') ? ' has-error' : '' }} ">
                            <label for="user" class="col-md-4 control-label">Users</label>
                            <div class="col-md-6">
                                <input type="hidden" name="user" value="{{$user[0]->id}}">
                                {{ $user[0]->authorised_person_name }}
                            </div>      
                            
                        </div>
                        <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }} required">
                            <label for="role" class="col-md-4 control-label">Roles</label>

                            <div class="col-md-6">
                                @foreach($role as $role)
                                <div class="col-md-12">
                                <input type="checkbox" name="role[]"  @if(old('role',in_array($role->id,$RoleUserId))) checked="checked" @endif value="{{$role->id}}">{{$role->name }}
                            </div>
                                 @endforeach

                                @if ($errors->has('role'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('role') }}</strong>
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
@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
      <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script>
        $(function() {
            $('input[name="authorised_person_dob"]').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:-18"
            });
        });

    </script>
@endsection