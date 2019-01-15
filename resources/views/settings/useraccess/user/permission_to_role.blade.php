@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
     <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Settings | User Access | Assigned Permission To Role</div>

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
                            Assigned Permission To Role
                            
                             </div>
                              <div class="panel-body">
                             <form class="form-horizontal" method="POST" action="{{ route('settings.user.assigned-permission-role', ['id' => $role->id])}}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }} ">
                            <label for="role" class="col-md-4 control-label">Role </label>
                            <input type="hidden" name="role" value="{{$role->id}}">
                            {{$role->name}}
                            
                             </div>
                            
                        <div class="form-group {{ $errors->has('permission') ? ' has-error' : '' }} required">
                            <label for="permission" class="col-md-4 control-label">Permission</label>
                            <div class="col-md-6">
                               
                                @foreach($permission as $permission)
                                <div class="col-md-12">
                                <input type="checkbox" name="permission[]" @if(old('permission',in_array($permission->id,$PermissionRoleId))) checked="checked" @endif @if($permission->id == "2") disabled="disabled" @endif value="{{$permission->id}}" >{{ $permission->display_name }}
                            </div>
                                @endforeach
                                @if ($errors->has('permission'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('permission') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success">
                                        Submit
                                    </button>
                                     <a href="{{route('settings.role')}}" class="btn btn-sm btn-danger add_more" data-form="add_more_form_lease_basis">Cancel</a>
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