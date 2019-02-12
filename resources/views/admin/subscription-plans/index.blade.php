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
                    { "data": "price" },
                    { "data": "available_leases"},
                    { "data": "available_users"},
                    { "data" : "hosting_type"},
                    { "data" : "validity"},
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
                        "targets" : 7,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit Email Template' type=\"button\" data-template='"+full['id']+"' class=\"btn btn-success edit_template\">Edit</button>";
                            html += "&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Preview Email Template' type=\"button\" data-template='"+full['template_code']+"' class=\"btn btn-primary preview_template\">Preview</button>"
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('admin.subscriptionplans.fetch') }}"

            });

            $(document.body).on("click", ".preview_template", function () {
                window.open("/admin/email-templates-preview/"+$(this).data('template').toLowerCase());
            });

            $(document.body).on("click", ".edit_template", function () {
                location.href = "/admin/email-template-edit/"+$(this).data('template');
            });
        });
    </script>
@endsection