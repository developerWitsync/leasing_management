@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
        <style>
        td.details-control {
            background: url('{{ asset('assets/plugins/datatables/img/details_open.png') }}') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url(' {{ asset("assets/plugins/datatables/img/details_close.png") }}') no-repeat center center;
        }
    </style>
    <!-- END CSS for this page -->
@endsection
@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">User Access | Manage User</div>
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
                             User List
                             <span>
                                <a href="{{ route('settings.user.create') }}" class="btn btn-sm btn-primary pull-right add_more" data-form="add_more_no_of_underlying_asset">Add User</a>
                             </span>
                             </div>
                              <div class="panel-body">
                                <div class="table-responsive">
                                <table cellpadding="0" cellspacing="0" id="user_table" class="table table-bordered table-hover display">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Name</th>
                                        <th>Roles</th>
                                        <th>Eamil</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
     <script>
        $(document).ready(function () {
        
            var user_table = $('#user_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {

                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "authorised_person_name" },
                    { "data": "roles" },
                    { "data": "email"},
                    { "data": "is_verified"},
                    { "data": "id" }
                ],
                "columnDefs": [
                    {
                        "targets" : 4,
                        "data" : "is_verified",
                        "render" : function(data, type, full, meta){
                            var html = "";
                            if(full["is_verified"] == "0"){
                                return "<button type=\"button\" data-user='"+full['id']+"'  data-is_verified=\"1\" class=\"btn btn-success status-update waves-effect\">Activate</button>";
                            } else {
                                return "<button type=\"button\" data-user='"+full['id']+"' data-is_verified=\"0\" class=\"btn btn-danger status-update waves-effect\">Disable</button>";
                            }
                        }
                    },
                    {
                        "targets" : 5,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit User' type=\"button\" data-user='"+full['id']+"' class=\"btn btn-sm btn-success edit_user\"><i class=\"fa fa-pencil-square-o\"></i></button>";
                            html += "&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Delete User' type=\"button\" data-user='"+full['id']+"' class=\"btn btn-sm btn-danger delete_user\">  <i class=\"fa fa-trash-o\"></i> </button>"
                             html += "&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Assigned Roles' type=\"button\" data-role='"+full['id']+"' class=\"btn btn-sm btn-success role_user\">  <i class=\"fa fa-address-book\"></i> </button>"
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('settings.user.fetch') }}"

            });

            $(document.body).on("click", ".edit_user", function () {
                window.location.href ="/settings/user-access/update/"+$(this).data('user');
            });

            $(document.body).on("click", ".role_user", function () {
                window.location.href ="/settings/user-access/assigned-role-user/"+$(this).data('role');
            });

            $(document.body).on("click", ".delete_user", function () {
                var user_id = $(this).data('user');
                bootbox.confirm({
                    message: "Are you sure that you want to delete this? These changes cannot be reverted.",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result) {
                            $.ajax({
                                url : "/settings/user-access/delete/"+user_id,
                                type : 'delete',
                                dataType : 'json',
                                success : function (response) {
                                    if(response['status']) {
                                        user_table.ajax.reload();
                                    }
                                }
                            })
                        }
                    }
                });
            });

            $(document.body).on("click", ".status-update", function () {
                $.ajax({
                    url : "{{ route('settings.user.updatestatus') }}",
                    data : {
                        id : $(this).data('user'),
                        is_verified : $(this).data('is_verified')
                    },
                    type : 'post',
                    dataType : 'json',
                    success : function (response) {
                        user_table.ajax.reload();
                    }
                })
            });
        });
    </script>
@endsection