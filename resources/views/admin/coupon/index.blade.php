@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Manage Coupon Codes
@endsection

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
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb-holder">
                    <h1 class="main-title float-left">Manage Coupon Codes</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Manage Coupon Codes</li>
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

        @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                <p>{{ session()->get('error') }}</p>
            </div>
        @endif

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3><i class="fa fa-file-o"></i> Coupon Codes </h3>
                        Admin can manage the coupon codes. Coupon code can be created specifically for some user and for all the users as well. In case you want to create the coupon code for all the users please search for the user by typing in the email address of the user.
                    </div>

                    <div class="card-body">
                        <table cellpadding="0" cellspacing="0" id="coupon_codes_table" class="table table-bordered table-hover display">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Code</th>
                                <th>Created For User</th>
                                <th>Created For Plan</th>
                                <th>Number of Uses</th>
                                <th>Status</th>
                                <th>Discount</th>
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

            var coupon_codes_table = $('#coupon_codes_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "code" },
                    { "data": "user_id" },
                    { "data": "plan_id" },
                    { "data": "no_of_uses" },
                    { "data": "status"},
                    { "data" : "discount", render : function (data, type, full, meta) {
                            return data+" %";
                        }
                    },
                    { "data": "id" }
                ],
                "columnDefs": [
                    {
                       "targets" : 2,
                       "data"  : "user_id",
                        "render" : function (data, type, full,meta) {
                            if(full['user_id']){
                                return full['user']['email'];
                            } else {
                                return "All";
                            }
                        }
                    },
                    {
                        "targets" : 3,
                        "data"  : "plan_id",
                        "render" : function (data, type, full,meta) {
                            if(full['plan_id']){
                                return full['plan']['title'];
                            } else {
                                return "All";
                            }
                        }
                    },
                    {
                        "targets" : 5,
                        "data" : "status",
                        "render" : function(data, type, full, meta){
                            var html = "";
                            if(full["status"] == "0"){
                                return "<button type=\"button\" data-coupon='"+full['id']+"'  data-status=\"1\" class=\"btn btn-success status-update waves-effect\">Enable</button>";
                            } else {
                                return "<button type=\"button\" data-coupon='"+full['id']+"' data-status=\"0\" class=\"btn btn-danger status-update waves-effect\">Disable</button>";
                            }
                        }
                    },
                    {
                        "targets" : 7,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit Coupon' type=\"button\" data-coupon='"+full['id']+"' class=\"btn btn-success edit_coupon\"><i class=\"fa fa-pencil-square-o fa-lg\"></i> Edit</button>";
                            html += "&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Delete Coupon' type=\"button\" data-coupon='"+full['id']+"' class=\"btn btn-danger delete_coupon\">  <i class=\"fa fa-trash-o fa-lg\"></i> Delete</button>"
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('admin.coupon.fetch') }}"

            });

            $(document.body).on("click", ".edit_coupon", function () {
                window.location.href ="/admin/coupon-codes/update/"+$(this).data('coupon');
            });

            $(document.body).on("click", ".delete_coupon", function () {
                var coupon_id = $(this).data('coupon');
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
                                url : "/admin/coupon-codes/delete/"+coupon_id,
                                type : 'delete',
                                dataType : 'json',
                                success : function (response) {
                                    if(response['status']) {
                                        coupon_codes_table.ajax.reload();
                                    }
                                }
                            })
                        }
                    }
                });
            });

            $(document.body).on("click", ".status-update", function () {
                $.ajax({
                    url : "{{ route('admin.coupon.updatestatus') }}",
                    data : {
                        id : $(this).data('coupon'),
                        status : $(this).data('status')
                    },
                    type : 'post',
                    dataType : 'json',
                    success : function (response) {
                        coupon_codes_table.ajax.reload();
                    }
                })
            });
        });
    </script>
@endsection