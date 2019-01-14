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
                @include('settings._menubar')
                 <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                     <div class="panel panel-info">
                            <div class="panel-heading">
                             Role List
                             <span>
                                <a href="{{ route('settings.role.create') }}" class="btn btn-sm btn-primary pull-right add_more" data-form="add_more_no_of_underlying_asset">Add Role</a>
                             </span>
                             </div>
                              <div class="panel-body">
                                <div class="table-responsive">
                                <table cellpadding="0" cellspacing="0" id="role_table" class="table table-bordered table-hover display">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Name</th>
                                        <th>Permissions</th>
                                        <th width="30%">Description</th>
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
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
     <script>
        $(document).ready(function () {
        
            var role_table = $('#role_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "name" },
                    { "data": "permissions" },
                    { "data": "description"},
                    { "data": "id" }
                ],
                "columnDefs": [
                    {
                        "targets" : 4,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            if(full['name'] != 'super_admin') {
                                var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit Role' type=\"button\" data-role='"+full['id']+"' class=\"btn btn-sm btn-success edit_role\"><i class=\"fa fa-pencil-square-o fa-lg\"></i> </button>";
                                html += "&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Delete Role' type=\"button\" data-role='"+full['id']+"' class=\"btn btn-sm btn-danger delete_role\">  <i class=\"fa fa-trash-o fa-lg\"></i> </button>"
                                html += "&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Assigned Permission' type=\"button\" data-role='"+full['id']+"' class=\"btn btn-sm btn-success permission_role\">  <i class=\"fa fa-address-book\"></i> </button>"
                                return html;
                            } else {
                                var html = "<button  disabled='disabled' data-toggle='tooltip' data-placement='top' title='Edit Role' type=\"button\" data-role='"+full['id']+"' class=\"btn btn-sm btn-success\"><i class=\"fa fa-pencil-square-o fa-lg\"></i></button>";
                                html += "&nbsp;<button disabled='disabled' data-toggle='tooltip' data-placement='top' title='Delete Role' type=\"button\" data-role='"+full['id']+"' class=\"btn btn-sm btn-danger\"><i class=\"fa fa-trash-o fa-lg\"></i></button>"
                                html += "&nbsp;<button disabled='disabled' data-toggle='tooltip' data-placement='top' title='Assigned Permission' type=\"button\" data-role='"+full['id']+"' class=\"btn btn-sm btn-success\"><i class=\"fa fa-address-book\"></i></button>"
                                return html;
                            }
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('settings.role.fetch') }}"

            });

            $(document.body).on("click", ".edit_role", function () {
                window.location.href ="/settings/user-access/role/update/"+$(this).data('role');
            });

            $(document.body).on("click", ".permission_role", function () {
                window.location.href ="/settings/user-access/assigned-permission-role/"+$(this).data('role');
            });

            $(document.body).on("click", ".delete_role", function () {
                var role_id = $(this).data('role');
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
                                url : "/settings/user-access/role/delete/"+role_id,
                                type : 'delete',
                                dataType : 'json',
                                success : function (response) {
                                    if(response['status']) {
                                        role_table.ajax.reload();
                                    }
                                }
                            })
                        }
                    }
                });
            });
        });
    </script>
@endsection