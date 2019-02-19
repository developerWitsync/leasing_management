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
        <div class="panel-heading">Lease Payments</div>

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
                    <form action="{{ route('lease.payments.savetotalpayments', ['id' => $asset->id]) }}" method="post" id="total_asset_payments">
                        {{ csrf_field() }}
                        <div>
                            Asset Name : <span class="badge badge-success">{{ $asset->name }}</span>
                        </div>
                       
                        <div>
                            Lease Start Date(Including Free Period) : <span class="badge badge-warning">{{ date('F,d Y', strtotime($asset->accural_period)) }}</span>
                        </div>

                        <div>
                            Lease End Date : <span class="badge badge-warning">{{ date('F,d Y', strtotime($asset->lease_end_date)) }}</span>
                        </div>

                        <div class="row form-group" style="margin-top: 12px;">
                            <label for="no_of_lease_payments" class="col-lg-2 col-md-4 leasepayLbl">Number of Lease Payments</label>
                            <div class="col-lg-3 col-md-6">
                                <select name="no_of_lease_payments" class="form-control">
                                    <option value="0" @if($subsequent_modify_required && $asset->total_payments > 0) disabled="disabled" @endif>--Select Number of Lease Payments--</option>
                                    @foreach($lease_asset_number_of_payments as $number_of_payment)
                                        <option value="{{ $number_of_payment['number'] }}" @if($asset->total_payments == $number_of_payment['number']) selected="selected" @endif @if($subsequent_modify_required && $number_of_payment['number'] < $asset->total_payments) disabled="disabled" @endif>{{ $number_of_payment['number'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    @if($asset->total_payments > 0)
                        <div class="panel panel-info">
                            <div class="panel-heading clearfix">
                                {{ $asset->name }} Payments
                                <span>
                                    <a href="{{ route('lease.payments.createassetpayment', ['id' => $asset->id]) }}" class="btn btn-sm btn-primary pull-right add_more">@if($asset->total_payments > 1) Add More @else Add Payment Details @endif</a>
                                </span>
                            </div>
                            <div class="panel-body">
                                <div class="addpayTbleOuter">
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
                    @endif
                    <div class="row">
                        <div class="col-md-12 align-right" >
                            <a href="{{ route('addlease.payments.index', ['id'=> $asset->lease->id]) }}" class="btn btn-primary">Go Back to Assets List</a>
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

            $('select[name="no_of_lease_payments"]').on('change', function(){
                $('#total_asset_payments').submit();
            });

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
                "ajax": "{{ route('lease.payments.fetchassetpayments', ['id' => $asset->id]) }}"

            });


            $(document.body).on('click', '.edit_asset_payment', function(){
                var payment_id = $(this).data('payment_id');
                window.location.href = '/lease/payments/update-asset-payment/{{ $asset->id }}/'+payment_id;
            });

        });

    </script>

@endsection