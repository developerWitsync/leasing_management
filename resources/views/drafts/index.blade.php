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
        <div class="panel-heading">Drafts</div>

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
                                Lease Drafts
                            </div>
                            <div class="panel-body">
                                <div class="panel-body">
                                    <table class="table table-condensed asset_payments_table">
                                        <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Payment Name</th>
                                            <th>Payment Type</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {

            // $('select[name="no_of_lease_payments"]').on('change', function(){
            //     $('#total_asset_payments').submit();
            // });

            var asset_payments_table = $('.asset_payments_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "name"},
                    { "data": "category.title", 'sortable': false},
                    { "data": "id"}
                ],
                "columnDefs": [
                    {
                        "targets" : 3,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit Asset Payment' type=\"button\" data-payment_id='"+full['id']+"' class=\"btn btn-sm  btn-success edit_asset_payment\"><i class=\"fa fa-pencil-square-o fa-lg\"></i></button>";
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('drafts.fetchleasedetails')}}"

            });


            
        });

    </script>

@endsection