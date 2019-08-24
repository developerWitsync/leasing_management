@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
@endsection
@section('content')
    <div class="leasingModuleOuter">
        <div class="leasingMainHd clearfix">{{ $asset->name }} <span> {{ $asset->category->title }} </span>
        </div>
        <div class="assetsNameOuter">
            <div class="assetsTabs">
                <ul>
                    @if(request()->segment(2) == 'valuation-capitalised')
                        <li>
                            <a href="{{ route('leasevaluation.cap.asset', ['id' => $lease->id]) }}">Overview</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.asset.valuation', ['id' => $lease->id]) }}">Valuation</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.interestdepreciation', ['id' => $lease->id]) }}" class="active">Interest &amp; Depreciation</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.expensereport', ['id' => $lease->id]) }}">Lease Expense</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('leasevaluation.ncap.asset', ['id' => $lease->id]) }}" class="active">Overivew</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.ncap.expensereport', ['id' => $lease->id]) }}">Lease Expense</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="tabBxOuter" style="display: block;" id="assetTab3">

                <div class="row">
                    <div class="col-md-4">
                        <ul class="nav nav-tabs">
                            <li class="@if(request()->get('currency') == 'lease_currency' || request()->get('currency') == '') active @endif">
                                @if(request()->segment(2) == 'valuation-capitalised')
                                    <a href="{{ route('leasevaluation.cap.interestdepreciation', ['id' => $lease->id, 'currency' => 'lease_currency']) }}">Lease Currency</a>
                                @endif
                            </li>
                            <li class="@if(request()->get('currency') == 'statutory_currency') active @endif">
                                @if(request()->segment(2) == 'valuation-capitalised')
                                    <a href="{{ route('leasevaluation.cap.interestdepreciation', ['id' => $lease->id, 'currency' => 'statutory_currency']) }}">Statutory Currency</a>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-8">
                        &nbsp;
                    </div>
                </div>
                <br>
                {{--<div class="leaseRafBx">--}}
                    {{--<div class="initialGraphBx">--}}
                        {{--<img src="/assets/images/initialGraph2.png">--}}
                    {{--</div>--}}
                {{--</div>--}}

                <!--Lease Valuation-->
                <div class="locatPurposeOutBx">
                    <div class="locatpurposeTop leaseterminatHd">
                        Lease Interest Expense
                        <a style="float: right;" href="{{ route('leasevaluation.cap.exportinterestdepreciation', ['id' => $lease->id]) }}">Export to Excel</a>
                    </div>
                    <div class="leasepaymentTble" style="overflow: auto;height: 500px;">
                        @include('leasevaluation.partials._interest_and_depreciation')
                    </div>
                </div>

                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading"><i class="fa fa-info-circle"></i>&nbsp;Working Disclaimer</h4>
                        <hr>
                        <p>There may be a possibility a very low balance amount in the closing lease liability appearing at the end of the lease term. We placed all checks in the system but due to longer lease duration, this closing lease liability balance may not be equated to “Zero” and very low balance left due to system calculations limited to 2 decimal places. Please consider the left-out balance in the closing lease liability under lease interest in the accounting at the end of the lease term.</p>
                        {{--<hr>--}}
                        {{--<p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p>--}}
                    </div>

            </div>


        </div>
    </div>

@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection