@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')

    @include('lease.lease-valuation._lease_liability')

    @include('lease.lease-valuation._lease_valuation')

    @include('lease.lease-valuation._impairment_test')

    <div class="form-group btnMainBx">
        <div class="col-md-6 btn-backnextBx">

            <a href="{{ route('addlease.leaseincentives.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
            <a href="{{ route('addlease.leasepaymentinvoice.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>

        </div>
        <div class="col-md-6 btnsubmitBx">
            &nbsp;
        </div>
    </div>

    <!--Lease Liability Calculus -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content current_modal_body">

            </div>
        </div>
    </div>
    <!--Lease Liability Calculus-->

@endsection
@section('footer-script')
    <script src="{{ asset('js/pages/lease_valuation.js') }}"></script>
@endsection