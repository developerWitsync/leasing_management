@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
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
        {{--<div class="panel-heading">Drafts</div>--}}

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

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">

                    <div class="row">
                        <div class="col-md-12 col-sm-12">

                            <div class="col-md-8" style="padding-left: 0px;padding-right: 0px;">
                                <h4>Lease Documents</h4>
                            </div>

                            <div class="col-md-4" style="text-align: right;padding-left: 0px;padding-right: 0px;">
                                <a href="{{ route('documents.index') }}">Go Back</a>
                            </div>
                            <table class="table subscription_table">
                                <tbody style="border: 1px solid #d1d1d1; background: #f9f9f9;">
                                <tr>
                                    <th>Lease Reference Number:</th>
                                    <td>{{ $model->singleAsset->uuid }}</td>
                                </tr>

                                <tr>
                                    <th>Lease Asset:</th>
                                    <td>{{ $model->singleAsset->name }}</td>
                                </tr>
                                <tr>
                                    <th>Lease Type:</th>
                                    <td>{{ $model->leaseType->title }}</td>
                                </tr>
                                <tr>
                                    <th>Lease Start Date:</th>
                                    <td>{{ \Carbon\Carbon::parse($model->singleAsset->lease_start_date)->format(config('settings.date_format')) }}</td>
                                </tr>
                                <tr>
                                    <th>Lease End Date:</th>
                                    <td>{{ \Carbon\Carbon::parse($model->singleAsset->lease_end_date    )->format(config('settings.date_format')) }}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong>Documents List</strong> |
                            <small>
                                Below is a list of all the documents that has been uploaded for this lease.
                            </small>
                        </div>
                        <div class="panel-body">
                            <div class="panel-body frmOuterBx">
                                <table class="table table-condensed drafts_table">
                                    <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Date Of Upload</th>
                                        <th>Document Type</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
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
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function () {

            var drafts_table = $('.drafts_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "upload_date"},
                    { "data": "type"},
                    { "data": "id"}
                ],
                "columnDefs": [
                    {
                        "targets" : 3,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                             var html = "<a href='"+full['file']+"' target='_blank' class='btn btn-sm btn-primary'><i class='fa fa-download'></i> Download</a>";
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('documents.index.fetchdocuments', ['id' => $model->id])}}"

            });

        });

    </script>

@endsection