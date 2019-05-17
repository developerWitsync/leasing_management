@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
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
                            <strong>Lease Liability (Contractual Lease Payments)</strong>
                            {{--|--}}
                            {{--<small>Drafts contains all the incomplete leases that has been created. If a lease is listed--}}
                                {{--in the drafts this means that the lease has not been submitted till now and making--}}
                                {{--changes to the lease are allowed.--}}
                            {{--</small>--}}
                        </div>
                        <div class="panel-body">
                            <div class="panel-body frmOuterBx">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form class="form-inline" action="" id="reports_filter">
                                            <div class="form-group">
                                                <label for="dt1">Start Date:</label>
                                                <input type="text" class="form-control" id="dt1" readonly="readonly" style="background-color: #fff;">
                                            </div>
                                            <div class="form-group">
                                                <label for="dt2">End Date:</label>
                                                <input type="text" class="form-control" id="dt2" readonly="readonly" style="background-color: #fff;">
                                            </div>
                                            <button type="submit" class="btn btn-default">Refresh</button>
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
    <script>
        var _ajax_url = '{{route("reports.leaseliability.fetchcontractual")}}';
    </script>
    <script src="{{ asset('js/pages/reports.js') }}"></script>
@endsection