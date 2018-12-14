@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | @lang('_page_titles.Email Templates')
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
                    <h1 class="main-title float-left">@lang('_emailtemplates.index.header')</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">@lang('_emailtemplates.index.header')</li>
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
                        <h3><i class="fa fa-file-o"></i> @lang('_emailtemplates.index.title') </h3>
                        @lang('_emailtemplates.index.instruction')
                    </div>

                    <div class="card-body">
                        <table cellpadding="0" cellspacing="0" id="email_templates_table" class="table table-bordered table-hover display">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Subject</th>
                                <th>Speacial Variables</th>
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

            var email_templates_table = $('#email_templates_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "title" },
                    { "data": "template_subject" },
                    { "data": "template_special_variables"},
                    { "data": "created_at"},
                    { "data": "id" }
                ],
                "columnDefs": [
                    {
                        "targets" : 5,
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
                "ajax": "{{ route('admin.emailtemplates.fetch') }}"

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