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
                            <a href="{{ route('leasevaluation.cap.asset', ['id' => $lease->id]) }}" class="active">Overview</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.asset.valuation', ['id' => $lease->id]) }}">Valuation</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.interestdepreciation', ['id' => $lease->id]) }}">Interest &amp; Depreciation</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.expensereport', ['id' => $lease->id]) }}">Lease Expense</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('leasevaluation.ncap.asset', ['id' => $lease->id]) }}">Overivew</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.ncap.expensereport', ['id' => $lease->id]) }}" class="active">Lease Expense</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="tabBxOuter" style="display: block;" id="assetTab3">
            {{--<div class="leaseRafBx">--}}
            {{--<div class="initialGraphBx">--}}
            {{--<img src="/assets/images/initialGraph2.png">--}}
            {{--</div>--}}
            {{--</div>--}}

            <!--Lease Valuation-->
                <div class="locatPurposeOutBx">
                    <div class="locatpurposeTop leaseterminatHd">
                        Lease Expense
                        <a style="float: right;" href="{{ route('leasevaluation.cap.exportleaseexpenseannexure', ['id' => $lease->id]) }}">Export to Excel</a>
                    </div>
                    <div class="leasepaymentTble" style="overflow: auto;height: 500px;">
                        @include('leasevaluation.partials._lease_expense_annexure')
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection