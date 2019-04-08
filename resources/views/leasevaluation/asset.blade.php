@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="leasingModuleOuter">
        <div class="leasingMainHd clearfix">{{ $asset->name }} <span> {{ $asset->category->title }} </span>
        </div>
        <div class="assetsNameOuter">
            <div class="assetsTabs">
                <ul>
                    <li><a href="#assetTab1" class="active">Overivew</a></li>
                    <li><a href="#assetTab2">Valuation</a></li>
                    <li><a href="#assetTab3">Interest &amp; Depreciation</a></li>
                </ul>
            </div>

            @include('leasevaluation.partials._asset_overview_tab')

            @include('leasevaluation.partials._valuation_tab')

            @include('leasevaluation.partials._interest_depreciation_tab')
        </div>
    </div>

@endsection
@section('footer-script')

    <script>
        $(document).ready(function () {
            $(".assetsTabs ul li a").on("click", function (e) {
                e.preventDefault();
                var webend = $(this).attr("href");
                $(".tabBxOuter").hide(0);
                $(webend).show(0);
                $(".assetsTabs ul li a").removeClass("active");
                $(this).addClass("active");
            });
        });
    </script>
@endsection