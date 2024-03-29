@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Select Discount Rate </div>

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
                <div role="tabpanel" class="tab-pane active">
                    <div class="panel panel-info">
                        <div class="panel-heading">Section A: Leases for own use lease</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Name of the Underlying Lease Asset</th>
                            <th>Underlying Lease Asset Classification</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $show_next1 = [];
                            @endphp
                            @if(count($own_assets)>0)
                            @foreach($own_assets as $key=>$asset)
                          <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    <td>
                                        {{ $asset->name }}
                                    </td>
                                    <td>
                                        {{ $asset->subcategory->title }}
                                    </td>
                                   <td>
                                        @if($asset->leaseSelectDiscountRate)
                                            @php
                                                $show_next1[] = true;
                                            @endphp
                                            <a class="btn btn-sm btn-primary" href="{{ route('addlease.discountrate.update', ['id'=> $asset->id]) }}">Update Select Discount Rate </a>
                                        @else
                                            @php
                                                $show_next1[] = false;
                                            @endphp
                                            <a class="btn btn-sm btn-primary" href="{{ route('addlease.discountrate.create', ['id'=> $asset->id]) }}">Add Select Discount Rate</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5"><center>No Record exsist.</center></td>
                                @endif
                        </tbody>
                    </table>
                </div>

            <div class="panel panel-info">
            <div class="panel-heading">Section B: Leases for sub leases use</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Name of the Underlying Lease Asset</th>
                            <th>Underlying Lease Asset Classification</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $show_next2 = [];
                            @endphp
                            @if(count($sublease_assets) >0)
                            @foreach($sublease_assets as $key=>$asset)
                          <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    <td>
                                        {{ $asset->name }}
                                    </td>
                                    <td>
                                        {{ $asset->subcategory->title }}
                                    </td>
                                   <td>
                                        @if($asset->leaseSelectDiscountRate)
                                            @php
                                                $show_next2[] = true;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.update', ['id'=> $asset->id]) }}">Update Select Low Value </a>
                                        @else
                                            @php
                                                $show_next2[] = false;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.create', ['id'=> $asset->id]) }}">Add Select Low Value</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5"><center>No Record exsist.</center></td>
                                @endif
                        </tbody>
                    </table>
            </div>
                    <div class="form-group">

                        <div class="col-md-6 col-md-offset-4">
                              <a href="{{ $back_url }}" class="btn btn-danger">Back</a>
                              @if($show_next)
                                    <a href="{{ route('addlease.balanceasondec.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
                              @endif
                            
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
