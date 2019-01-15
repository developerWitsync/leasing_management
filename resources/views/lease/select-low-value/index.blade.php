@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Add New Lease | Select Low Value </div>

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
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Name of the Underlying Lease Asset</th>
                            <th>Underlying Lease Asset Classification</th>
                            <th>Fair Market Value as on date of Lease Start</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $show_next = [];
                            @endphp
                            @if(count($assets) > 0)
                                @foreach($assets as $key=>$asset)
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
                                            {{ $asset->fairMarketValue->total_units }}
                                        </td>
                                        <td>
                                            @if($asset->leaseSelectLowValue)
                                                @php
                                                    $show_next[] = true;
                                                @endphp
                                                <a class="btn btn-sm btn-primary" href="{{ route('addlease.lowvalue.update', ['id'=> $asset->id]) }}">Update Select Low Value </a>
                                            @else
                                                @php
                                                    $show_next[] = false;
                                                @endphp
                                                <a class="btn btn-sm btn-primary" href="{{ route('addlease.lowvalue.create', ['id'=> $asset->id]) }}">Add Select Low Value</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">
                                        <center>No underlying asset falls in the criteria. Please click on the next button to proceed further.</center>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="form-group">

                        <div class="col-md-6 col-md-offset-4">
                              <a href="{{ route('lease.escalation.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
                              @if(!in_array(false, $show_next))
                                    <a href="{{ route('addlease.discountrate.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
                              @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
