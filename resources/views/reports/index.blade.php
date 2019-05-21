@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
@endsection
@section('title')
    Reports
@endsection
@section('content')
    <div class="panel panel-default">
        {{--<div class="panel-heading">My Profile</div>--}}

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

            <div class="">
                <div role="tabpanel" class="tab-pane active">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Reports
                        </div>
                        <div class="panel-body">
                            <div class="row" style="margin-left:20px; ">
                                <div class="col-md-4">
                                    <h4>Lease Overview</h4>
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0)" class="btn-link" title="Coming Soon" data-toggle="tooltip">
                                                Trial Balance
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="btn-link" title="Coming Soon" data-toggle="tooltip">
                                                Balance Sheet
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="btn-link" title="Coming Soon" data-toggle="tooltip">
                                                Profit & Loss
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="btn-link" title="Coming Soon" data-toggle="tooltip">
                                                Cash Flow Statement
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <h4>Register</h4>
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0)" class="btn-link" title="Coming Soon" data-toggle="tooltip">
                                                Lease Asset Register
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <h4>Lease Reports</h4>
                                    <ul>
                                        <li>
                                            <a href="{{route('reports.leaseliability.contractual')}}" class="btn-link">
                                                Lease Liability (Contractual Lease Payments)
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="btn-link" title="Coming Soon" data-toggle="tooltip">
                                                Lease Liability (Actual Lease Payments)
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
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
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection