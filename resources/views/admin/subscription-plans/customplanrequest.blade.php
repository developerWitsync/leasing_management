@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Custom Plans Requests
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
                    <h1 class="main-title float-left">Custom Plans Requests</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Custom Plans Requests</li>
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
                        <h3><i class="fa fa-file-o"></i> Custom Plans Requests </h3>
                        Admin can view the custom plans requests from the users.
                    </div>

                    <div class="card-body">
                        <table cellpadding="0" cellspacing="0" id="custom_plans_table" class="table table-bordered table-hover display">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Number Of Lease Assets Required</th>
                                <th>Number Of Sub-Users Required</th>
                                <th>Type Of Hosting</th>
                                <th>Message</th>
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
            $('#custom_plans_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "name" },
                    { "data": "email" , render : function (data, type, row, meta) {
                            return "<a href='mailto:"+data+"'>"+data+"</a>";
                        }
                    },
                    { "data": "no_of_lease_assets" },
                    { "data": "no_of_users" },
                    { "data" : "hosting_type"},
                    { "data" : "comments"},
                    { "data" : "id" }
                ],
                columnDefs : [
                    {
                        "targets" : 7,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = "<button data-toggle='tooltip' data-placement='top' title='Create Custom Plan' type=\"button\" data-request='"+full['id']+"' class=\"btn btn-success create_custom_plan\">Create Custom Plan</button>";
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('admin.subscriptionplans.fetchcustomplanrequests') }}"

            });

            $(document.body).on("click", ".create_custom_plan", function () {
                location.href = "/admin/subscription-plans/create-custom-plan/"+$(this).data('request');
            });

        });
    </script>
@endsection