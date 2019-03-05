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

            @include('lease._subsequent_details')

            {{--@include('lease._menubar')--}}
            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane FrmOuterBx active">
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Lease Asset Classification</th>
                            <th> Lease Asset</th>
                            <th>Lease Assets of Similar Characteristics Units</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lease->assets as $key=>$asset)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                               
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

<div class="form-group btnMainBx">

    <div class="col-md-4 col-sm-4 btn-backnextBx">
        <a href="{{ $back_url }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
    </div>
    
    <div class="col-md-6 col-sm-6 btn-backnextBx rightlign ">
        <a href="{{ route('addlease.fairmarketvalue.index', ['id' => $lease->id]) }}" class="btn btn-primary save_next"> {{ env('NEXT_LABEL') }} <i class="fa fa-arrow-right"></i></a>
    </div>
 
</div>
            </div>

        </div>
    </div>
@endsection