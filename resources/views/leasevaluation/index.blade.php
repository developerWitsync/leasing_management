@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
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
                                @if($capitalized == '1')
                                    <thead>
                                    <tr>
                                        <th colspan="10"></th>
                                        <th colspan="7" class="foriegn initial_valuation">In Lease Foreign Currency</th>
                                        <th colspan="9" class="reporting initial_valuation">In Reporting Currency</th>
                                    </tr>
                                    <tr>
                                        <th colspan="10"></th>
                                        <th colspan="4" class="foriegn initial_valuation">Initial Valuation</th>
                                        <th colspan="3" class="foriegn subsequent_valuation">Subsequent Valuation</th>

                                        <th colspan="4" class="reporting initial_valuation">Initial Valuation</th>
                                        <th colspan="5" class="reporting subsequent_valuation">Subsequent Valuation</th>

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
                                        <th>Remaining Lease </th>
                                        <th>Discount Rate</th>

                                        <th class="foriegn initial_valuation">Lease Currency</th>
                                        <th class="foriegn initial_valuation">UD Lease Liability</th>
                                        <th class="foriegn initial_valuation">PV Lease Liability</th>
                                        <th class="foriegn initial_valuation">Value of Lease Asset</th>

                                        <th class="foriegn subsequent_valuation">UD Lease Liability</th>
                                        <th class="foriegn subsequent_valuation">PV Lease Liability</th>
                                        <th class="foriegn subsequent_valuation">Value of Lease Asset</th>

                                        <th class="reporting initial_valuation">Exchange Rate</th>
                                        <th class="reporting initial_valuation">UD Lease Liability</th>
                                        <th class="reporting initial_valuation">PV Lease Liability</th>
                                        <th class="reporting initial_valuation">Value of Lease Asset</th>

                                        <th class="reporting subsequent_valuation">Effective Date</th>
                                        <th class="reporting subsequent_valuation">Exchange Rate</th>
                                        <th class="reporting subsequent_valuation">UD Lease Liability</th>
                                        <th class="reporting subsequent_valuation">PV Lease Liability</th>
                                        <th class="reporting subsequent_valuation">Value of Lease Asset</th>
                                    </tr>
                                    </thead>
                                @else
                                    <thead>
                                        <tr>
                                            <th colspan="10"></th>
                                            <th colspan="3" class="foriegn initial_valuation">In Lease Foreign Currency</th>
                                            <th colspan="5" class="reporting initial_valuation">In Reporting Currency</th>
                                        </tr>
                                        <tr>
                                            <th colspan="10"></th>
                                            <th colspan="2" class="foriegn initial_valuation">Initial Valuation</th>
                                            <th colspan="1" class="foriegn subsequent_valuation">Subsequent Valuation</th>

                                            <th colspan="2" class="reporting initial_valuation">Initial Valuation</th>
                                            <th colspan="3" class="reporting subsequent_valuation">Subsequent Valuation</th>

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
                                            <th>Remaining Lease </th>
                                            <th>Discount Rate</th>

                                            <th class="foriegn initial_valuation">Lease Currency</th>
                                            <th class="foriegn initial_valuation">Ud Lease Liability</th>

                                            <th class="foriegn subsequent_valuation">UD Lease Liability</th>


                                            <th class="reporting initial_valuation">Exchange Rate</th>
                                            <th class="reporting initial_valuation">UD Lease Liability</th>

                                            <th class="reporting subsequent_valuation">Effective Date</th>
                                            <th class="reporting subsequent_valuation">Exchange Rate</th>
                                            <th class="reporting subsequent_valuation">UD Lease Liability</th>
                                        </tr>
                                    </thead>
                                @endif
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
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/pages/valuation_main.js') }}"></script>
    <script>
        var _data_table_url = "{{ route('leasevaluation.fetchassets',['capitalized' => $capitalized, 'category_id' => (request()->has('id'))?request()->id:'all']) }}";
                @if($capitalized == '1')
                    var _is_capitalized = true;
                @else
                    var _is_capitalized = false;
                @endif
    </script>
    @if (session('status'))
        <script>
            $(function () {
                bootbox.dialog({
                    message: '<div class="thank-you-pop">\n' +
                        '\t\t\t\t\t\t\t<img src="{{ asset('images/round_tick.png') }}" alt="">\n' +
                        '\t\t\t\t\t\t\t<h1>Thank You!</h1>\n' +
                        '\t\t\t\t\t\t\t<p>Your Lease has been submitted successfully.</p>\n' +
                        '\t\t\t\t\t\t\t<h3 class="cupon-pop">Lease ULA CODE : <span>{{ session('status') }}</span></h3>\n' +
                        '\t\t\t\t\t\t\t\n' +
                        ' \t\t\t\t\t\t</div>',
                    closeButton: true
                });
            });
        </script>
    @endif
@endsection
