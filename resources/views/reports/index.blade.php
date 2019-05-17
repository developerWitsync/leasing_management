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
                            <div class="row">
                                <div class="col-md-4">
                                    <ul>
                                        <li>a</li>
                                        <li>b</li>
                                        <li>c</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <ul>
                                        <li>a</li>
                                        <li>b</li>
                                        <li>c</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <ul>
                                        <li>
                                            <a href="{{route('reports.leaseliability.contractual')}}" class="btn-link">Lease Liability (Contractual Lease Payments)</a>
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
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
@endsection