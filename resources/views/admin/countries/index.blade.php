@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Manage Countries
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
                    <h1 class="main-title float-left">Manage Countries</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Manage Countries</li>
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
                <h4 class="alert-heading">Trumbowyg - A lightweight WYSIWYG editor</h4>
                <p>Light, translatable and customisable jQuery plugin. Beautiful design, generates semantic code, comes with a powerful API. <a target="_blank" href="https://alex-d.github.io/Trumbowyg/">Trumbowyg Documentation</a></p>
            </div>
        @endif

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3><i class="fa fa-file-o"></i> Countries </h3>
                        Admin can manage the countries from here. Every country should have unique ISO CODE and unique name.
                    </div>

                    <div class="card-body">
                        <table cellpadding="0" cellspacing="0" id="countries_table" class="table table-bordered table-hover display">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>ISO CODE</th>
                                <th>Status</th>
                                <th>Created At</th>
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

            var countries_table = $('#countries_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "name" },
                    { "data": "iso_code" },
                    { "data": "status"},
                    { "data": "created_at"},
                    { "data": "id" }
                ],
                "columnDefs": [
                    {
                        "targets" : 3,
                        "data" : "status",
                        "render" : function(data, type, full, meta){
                            var html = "";
                            if(full["status"] == "0"){
                                return "<button type=\"button\" data-country='"+full['id']+"'  data-status=\"1\" class=\"btn btn-success status-update waves-effect\">Enable</button>";
                            } else {
                                return "<button type=\"button\" data-country='"+full['id']+"' data-status=\"0\" class=\"btn btn-danger status-update waves-effect\">Disable</button>";
                            }
                        }
                    },
                    {
                        "targets" : 5,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit Country' type=\"button\" data-country='"+full['id']+"' class=\"btn btn-success edit_country\"><i class=\"fa fa-pencil-square-o fa-lg\"></i> Edit</button>";
                            html += "&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Delete Country' type=\"button\" data-country='"+full['id']+"' class=\"btn btn-danger delete_country\">  <i class=\"fa fa-trash-o fa-lg\"></i> Delete</button>"
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('admin.countries.fetch') }}"

            });

            $(document.body).on("click", ".edit_country", function () {
                window.location.href ="/admin/country/update/"+$(this).data('country');
            });

            $(document.body).on("click", ".delete_country", function () {
                var country_id = $(this).data('country');
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
                                url : "/admin/country/delete/"+country_id,
                                type : 'delete',
                                dataType : 'json',
                                success : function (response) {
                                    if(response['status']) {
                                        countries_table.ajax.reload();
                                    }
                                }
                            })
                        }
                    }
                });
            });

            $(document.body).on("click", ".status-update", function () {
                $.ajax({
                    url : "{{ route('admin.countries.updatestatus') }}",
                    data : {
                        id : $(this).data('country'),
                        status : $(this).data('status')
                    },
                    type : 'post',
                    dataType : 'json',
                    success : function (response) {
                        countries_table.ajax.reload();
                    }
                })
            });
        });
    </script>
@endsection