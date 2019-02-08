@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Lease Valuation</div>
        <div class="">
            <ul class="nav nav-tabs">
                <li @if((!request()->has('capitalized')) || (request()->has('capitalized') && request()->query('capitalized') == 1)) class="active" @endif>
                    <a href="{{ route('leasevaluation.index',['capitalized' => 1]) }}">Capitalized Lease Asset</a></li>
                <li @if(request()->has('capitalized') && request()->query('capitalized') == 0) class="active" @endif><a
                            href="{{ route('leasevaluation.index',['capitalized' => 0]) }}">Non-Capitalized Lease
                        Asset</a></li>
            </ul>
        </div>

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
                    @include('leasevaluation._menubar')
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <table id="lease_valuation" class="table table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="10"></th>
                                        <th colspan="4" class="initial_valuation">Initial Valuation</th>
                                        <th colspan="3" class="subsequent_valuation">Subsequent Valuation</th>
                                    </tr>
                                    <tr>
                                        <th>Reference No.</th>
                                        <th>Lessor</th>
                                        <th>Lease Asset</th>
                                        <th>Type</th>
                                        <th>Purpose</th>
                                        <th>Country</th>
                                        <th>Location</th>
                                        <th>Lease Start Date</th>
                                        <th>Remaining Lease Term</th>
                                        <th>Discount Rate</th>

                                        <th class="initial_valuation">Lease Currency</th>
                                        <th class="initial_valuation">Undiscounted Lease Liability</th>
                                        <th class="initial_valuation">Present Value of Lease Liability</th>
                                        <th class="initial_valuation">Value of Lease Asset</th>

                                        <th class="subsequent_valuation">Undiscounted Lease Liability</th>
                                        <th class="subsequent_valuation">Present Value of Lease Liability</th>
                                        <th class="subsequent_valuation">Value of Lease Asset</th>
                                    </tr>
                                </thead>
                            </table>
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
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/pages/valuation_main.js') }}"></script>
    <script>
        var _data_table_url = "{{ route('leasevaluation.fetchassets',['capitalized' => $capitalized, 'category_id' => (request()->has('id'))?request()->id:'all']) }}";
    </script>
@endsection
