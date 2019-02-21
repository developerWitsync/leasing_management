@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
     <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Settings | User Access | Edit User</div>

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
                            Edit User
                            
                             </div>
                              <div class="panel-body">
                                  @include('settings.useraccess.user._form')
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