@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
@endsection
@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Settings | User Access | Create Role</div>

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
         <!--    @include('settings._menubar') -->
                 <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                     <div class="panel panel-info">
                            <div class="panel-heading">
                            Add Role
                            
                             </div>
                              <div class="panel-body">
                             <form class="form-horizontal" method="POST" action="{{ route('settings.role.create') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} required">
                                <label for="name" class="col-md-4 control-label">Name</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="name" type="text" placeholder="Name" class="form-control" name="name" value="" >
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                             <div class="form-group{{ $errors->has('display_name') ? ' has-error' : '' }} required">
                                <label for="display_name" class="col-md-4 control-label">Display Name</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="display_name" type="text" placeholder="Display Name" class="form-control" name="display_name" value="" >
                                    </div>
                                    @if ($errors->has('display_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('display_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                             <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }} ">
                                <label for="description" class="col-md-4 control-label">Description</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <textarea id="description"  placeholder="Description" class="form-control" name="description" ></textarea>
                                    </div>
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
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
@endsection