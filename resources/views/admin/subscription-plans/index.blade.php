@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Manage Subscription Plans
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
                    <h1 class="main-title float-left">Manage Subscription Plans</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Manage Subscription Plans</li>
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
                        <h3><i class="fa fa-file-o"></i> Subscription Plans </h3>
                        Admin can manage the Subscription plans here and can change the features for any subscription plan.
                    </div>

                    <div class="card-body">
                        <table cellpadding="0" cellspacing="0" id="plans_table" class="table table-bordered table-hover display">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Plan Name</th>
                                <th>Price</th>
                                <th>Available Lease Assets</th>
                                <th>Allowed Sub-users</th>
                                <th>Type Of Hosting</th>
                                <th>Validity(In Days)</th>
                                <th>Annual Discount</th>
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

            function checkForUnlimited(data, type, row){
                if(data){
                    return data;
                } else {
                    return "Unlimited";
                }
            }

            var plans_table = $('#plans_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "title" },
                    { "data": "price" , "render": function (data, type, row) {
                            if(row['price_plan_type'] == '1' && row['is_custom'] == '0'){
                                //Non-Customizable
                                if(data){
                                    return "$ "+data;
                                } else {
                                    return "Free";
                                }
                            } else {
                                if(row['is_custom'] == '1'){
                                    return "$ "+data;
                                } else {
                                    //Customizable
                                    return "N/A";
                                }
                            }
                        }
                    },
                    {
                        "data": "available_leases", render : function(data, type, row){
                            return checkForUnlimited(data, type, row);
                        }
                    },
                    {
                        "data": "available_users" , render : function(data, type, row){
                            return checkForUnlimited(data, type, row);
                        }
                    },
                    { "data" : "hosting_type"},
                    {
                        "data" : "validity", render : function(data, type, row){
                            return checkForUnlimited(data, type, row);
                        }
                    },
                    {
                        "data" : "annual_discount", render : function (data, type, row) {
                            return data+" %";
                        }
                    },
                    { "data": "id" }
                ],
                "columnDefs": [
                    {
                        "targets" : 5,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                           if(full['hosting_type'] == 'cloud') {
                               return "Cloud Hosting";
                           } else if(full['hosting_type'] == 'on-premises'){
                               return "On Premises Hosting";
                           } else {
                               return "Cloud/On-Premise Hosting";
                           }
                        }
                    },
                    {
                        "targets" : 8,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit Subscription Plan' type=\"button\" data-plan='"+full['id']+"' class=\"btn btn-success edit_plan\">Edit</button>";
                            // if(full['is_custom'] == '1'){
                                html += "&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Delete Custom Subscription Plan' type=\"button\" data-plan='"+full['id']+"' class=\"btn btn-danger delete_plan\">Delete</button>";
                            // }
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('admin.subscriptionplans.fetch') }}"

            });

            $(document.body).on("click", ".edit_plan", function () {
                location.href = "/admin/subscription-plans/update/"+$(this).data('plan');
            });

            $(document.body).on("click", ".delete_plan", function () {
                var country_id = $(this).data('plan');
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
                                url : "/admin/subscription-plans/delete/"+country_id,
                                type : 'delete',
                                dataType : 'json',
                                success : function (response) {
                                    if(response['status']) {
                                        plans_table.ajax.reload();
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