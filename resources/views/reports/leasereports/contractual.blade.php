@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>

    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/buttons.dataTables.min.css') }}"/>

    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
    <style>
        div.dt-button-collection {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 311px !important;
            margin-top: 3px !important;
            padding: 8px 8px 4px 8px !important;
            border: 1px solid #ccc !important;
            border: 1px solid rgba(0,0,0,0.4) !important;
            background-color: white !important;
            overflow: hidden !important;
            z-index: 2002 !important;
            border-radius: 5px !important;
            box-shadow: 3px 3px 5px rgba(0,0,0,0.3) !important;
            -webkit-column-gap: 8px !important;
            -moz-column-gap: 8px !important;
            -ms-column-gap: 8px !important;
            -o-column-gap: 8px !important;
            column-gap: 8px !important;
            height: 300px !important;
            overflow-y: scroll !important;
        }
    </style>
@section('title')
    Reports
@endsection
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
                            <strong>Lease Report (Contractual Lease Payments)</strong>
                            {{--|--}}
                            {{--<small>Drafts contains all the incomplete leases that has been created. If a lease is listed--}}
                                {{--in the drafts this means that the lease has not been submitted till now and making--}}
                                {{--changes to the lease are allowed.--}}
                            {{--</small>--}}
                        </div>
                        <div class="panel-body">
                            <div class="panel-body frmOuterBx">

                                <div class="row LibfilterBx">
                                    <div class="col-md-12">
                                        <form class="form-inline" action="" id="reports_filter">
                                            <div class="form-group col-md-4">
                                                <label for="dt1">Start Date:</label>
                                                <input type="text" class="form-control" id="dt1" readonly="readonly" style="background-color: #fff;" placeholder="Select Start Date">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="dt2">End Date:</label>
                                                <input type="text" class="form-control" id="dt2" readonly="readonly" style="background-color: #fff;" placeholder="Select End Date">
                                            </div>
                                            <div class="form-group col-md-4 ">
                                                <button type="submit col-md-2" class="btn btn-success">Run</button>
                                                <a href="javascript:void(0);" id="clear_filters" class="btn-link">Clear Filters</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <table id="report_contractual" class="table table-condensed table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Reference Number</th>
                                        <th>Lessor Name</th>
                                        <th>Lease Asset</th>
                                        <th>Type</th>
                                        <th>Lease Start Date</th>
                                        <th>Lease End Date</th>
                                        <th>Country</th>
                                        <th>Location</th>
                                        <th>Purpose</th>
                                        <th>Initial PV Lease Liability</th>
                                        <th>Subsequent Increase / (Decrease)</th>
                                        <th>Latest PV of Lease Liability</th>
                                        <th>Lease Interest</th>
                                        <th>Contractual Lease Payments</th>
                                        <th>Closing Value of Lease Liability</th>
                                        <th>Initial Value of Lease Asset</th>
                                        <th>Latest Value of Lease Asset</th>
                                        <th>Depreciation</th>
                                        <th>Impairment if any</th>
                                        <th>Carrying Value of Lease Asset</th>
                                        <th>Adjustment to Opening Equity</th>
                                        <th>Charge to PL</th>
                                    </tr>
                                    </thead>
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
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/colvis.js') }}"></script>
    <script>
        var _ajax_url = '{{route("reports.leaseliability.fetchcontractual")}}';
        var _initial_url = '{{route("reports.leaseliability.fetchcontractual")}}';
    </script>
    <script src="{{ asset('js/pages/reports.js') }}"></script>
@endsection