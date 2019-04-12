@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
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
                            <strong>All Transactions </strong> |
                            <small>Here you can see the list of generated invoices for all the transactions.</small>
                        </div>
                        <div class="panel-body">
                            <div class="panel-body frmOuterBx">
                                <table class="table table-condensed transactions_table">
                                    <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Purchased Date</th>
                                        <th>Plan Name</th>
                                        <th>Invoice Number</th>
                                        <th>Payment Status</th>
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
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function () {

            var transactions_table = $('.transactions_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        sortable : false
                    },
                    { "data": "created_at", sortable : false},
                    { "data" : "subscription_package.title", sortable : false},
                    { "data": "invoice_number", sortable : false},
                    { "data": "payment_status", sortable : false},
                    { "data": "id", sortable : false}
                ],
                "columnDefs": [
                    {
                        "targets" : 5,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            if(full['payment_status'] == "Completed"){
                                var html = "<button  data-toggle='tooltip' data-placement='top' title='Download Invoice' type=\"button\" data-invoice_number='"+full['id']+"' class=\"btn btn-sm btn-info download_invoice\"><i class=\"fa fa-download\"></i></button>";
                                return html;
                            } else {
                                return '';
                            }
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('transactions.fetch')}}"
            });

            $(document.body).on('click', '.download_invoice', function(){
                var invoice_number = $(this).data('invoice_number');
                window.location.href = '/transactions/download-invoice/'+invoice_number;
            });

        });

    </script>

@endsection