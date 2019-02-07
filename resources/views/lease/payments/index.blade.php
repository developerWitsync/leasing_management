@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Lease Payments</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach (array_unique($errors->all()) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{--@include('lease._menubar')--}}
            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane FrmOuterBx active">
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Underlying Leased Asset Classification</th>
                            <th>Name of the Underlying Lease Asset</th>
                            <th>Number of Units of Lease Assets of Similar Characteristics</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lease->assets as $key=>$asset)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td style="width: 10%">
                                    {{ $asset->uuid}}
                                </td>
                                <td>
                                    {{ $asset->subcategory->title }}
                                </td>
                                <td>
                                    {{ $asset->name }}
                                </td>
                                <td>
                                    {{ $asset->similar_asset_items }}
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary modifypayBtn" href="{{ route('lease.payments.add', ['lease_id' => $lease->id, 'asset_id'=> $asset->id]) }}">Add/Modify Lease Payments</a>
                                   
                                    <span class="badge badge-warning exitingBtn">
                                        Existing Payments
                                        {{ count($asset->payments) }}
                                    </span>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                </div>

                <div class="form-group btnMainBx clearfix">
                    <div class="col-md-6 col-sm-6 btn-backnextBx">

                        <a href="{{ route('addlease.purchaseoption.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
                        @if($show_next)
                            <a href="{{ route('addlease.fairmarketvalue.index', ['id' => $lease->id]) }}"
                               class="btn btn-primary">Next</a>
                        @endif

                    </div>
                    <div class="col-md-6 col-sm-6 btnsubmitBx">
                        &nbsp;
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection