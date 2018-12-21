@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Manage Business Account
@endsection

@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb-holder">
                    <h1 class="main-title float-left">Manage Business Account</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Manage Business Account</li>
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

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3><i class="fa fa-file-o"></i> Manage Business Account </h3>
                        Admin can manage the users and can reset the password for any user, can activate/de-activate any account, can delete the user and can also make the changes to the user profile as well.
                    </div>

                    <div class="card-body">
                        <table cellpadding="0" cellspacing="0" id="users_table" class="table table-bordered table-hover display">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Legal Entity Name</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div><!-- end card-->
            </div>

        </div>
    </div>
@endsection
@section('footer-script')
    <script>
        $(document).ready(function () {

            var users_table = $('#users_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "legal_entity_name" },
                    { "data": "authorised_person_name" },
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
                            var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit User' type=\"button\" data-user='"+full['id']+"' class=\"btn btn-success edit_user\">Edit</button>";
                            html += "&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Delete User' type=\"button\" data-user='"+full['id']+"' class=\"btn btn-danger delete_user\">Delete</button>"
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('admin.users.fetch') }}"

            });

            $(document.body).on("click", ".edit_user", function () {
               location.href = "/admin/manage-user-edit/"+$(this).data('user');
            });

            $(document.body).on("click", ".delete_user", function () {
                var user_id = $(this).data('user');
                bootbox.confirm({
                    message: "Are you sure that you want to delete this user? These changes cannot be reverted.",
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
                                url : "/admin/manage-user-delete/"+user_id,
                                type : 'delete',
                                dataType : 'json',
                                success : function (response) {
                                    if(response['status']) {
                                        users_table.ajax.reload();
                                    }
                                }
                            })
                        }
                    }
                });
            });

            $(document.body).on("click", ".status-update", function () {
                $.ajax({
                    url : "{{ route('admin.users.updatestatus') }}",
                    data : {
                        id : $(this).data('user'),
                        is_verified : $(this).data('is_verified')
                    },
                    type : 'post',
                    dataType : 'json',
                    success : function (response) {
                        users_table.ajax.reload();
                    }
                })
            });

        });
    </script>
@endsection