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

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong>Lease Documents </strong> |
                            <small>
                                    All the documents that have been uploaded to the leases can be downloaded from here, new documents can also be uploaded for any lease from here as well. In order to view the documents for any of the lease please click on the view documents button.
                            </small>
                        </div>
                        <div class="panel-body">
                            <div class="panel-body frmOuterBx">
                                <table class="table table-condensed drafts_table">
                                    <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Lease Reference Number </th>
                                        <th>Lease Asset</th>
                                        <th>Lease Type</th>
                                        <th>Lease Start Date</th>
                                        <th>Lease End Date</th>
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
                        },
                        sortable : false
                    },
                    { "data": "uuid", sortable : false},
                    { "data": "name", sortable : false},
                    { "data": "contract_classification_title", sortable : false},
                    { "data": "lease_start_date", sortable : false},
                    { "data": "lease_end_date", sortable : false},
                    { "data": "id", sortable : false}
                ],
                "columnDefs": [
                    {
                        "targets" : 6,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = "<button  data-toggle='tooltip' data-placement='top' title='View Lease Documents' type=\"button\" data-lease_id='"+full['id']+"' class=\"btn btn-sm btn-primary view_documents\"><i class='fa fa-eye'></i> View Documents</button><a href=\"javascript:;\"";
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('documents.index.fetchleaseassets')}}"

            });

            $(document.body).on('click', '.view_documents', function () {
                var lease_id = $(this).data('lease_id');
                window.location.href = '/documents/list/' + lease_id;
            });

        });

    </script>

@endsection