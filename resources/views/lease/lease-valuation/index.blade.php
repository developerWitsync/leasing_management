@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Present Value of Lease Liability</div>

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
                            <th>Currency</th>
                            <!-- <th>Action</th> -->
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $show_next = [];
                            @endphp
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
                                    <td>{{ $asset->lease->lease_contract_id }}</td>
                                   <!-- <td>
                                        @if($asset->leaseSelectDiscountRate)
                                            @php
                                                $show_next[] = true;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.update', ['id'=> $asset->id]) }}">Update Select Discount Rate </a>
                                        @else
                                            @php
                                                $show_next[] = false;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.create', ['id'=> $asset->id]) }}">Add Select Discount Rate</a>
                                        @endif
                                    </td> -->
                                  
                                </tr>
                            @endforeach
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
                            <th>Currency</th>
                        
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $show_next = [];
                            @endphp
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
                                    <td>{{$asset->lease->lease_contract_id}}</td>
                                   <!-- <td>
                                        @if($asset->leaseSelectDiscountRate)
                                            @php
                                                $show_next[] = true;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.update', ['id'=> $asset->id]) }}">Update Select Low Value </a>
                                        @else
                                            @php
                                                $show_next[] = false;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.create', ['id'=> $asset->id]) }}">Add Select Low Value</a>
                                        @endif
                                    </td> -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
                    <div class="form-group">

                        <div class="col-md-6 col-md-offset-4">
                              <a href="{{ route('addlease.lowvalue.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
                              @if(!in_array(false, $show_next))
                                    <a href="#" class="btn btn-primary">Next</a>
                              @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

     <div class="panel panel-default">
        <div class="panel-heading">Value of Lease Asset</div>

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
                                $show_next = [];
                            @endphp
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
                                                $show_next[] = true;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.update', ['id'=> $asset->id]) }}">Update Select Discount Rate </a>
                                        @else
                                            @php
                                                $show_next[] = false;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.create', ['id'=> $asset->id]) }}">Add Select Discount Rate</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
                                $show_next = [];
                            @endphp
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
                                                $show_next[] = true;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.update', ['id'=> $asset->id]) }}">Update Select Low Value </a>
                                        @else
                                            @php
                                                $show_next[] = false;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.create', ['id'=> $asset->id]) }}">Add Select Low Value</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
                    <div class="form-group">

                        <div class="col-md-6 col-md-offset-4">
                              <a href="{{ route('addlease.lowvalue.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
                              @if(!in_array(false, $show_next))
                                    <a href="#" class="btn btn-primary">Next</a>
                              @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

     <div class="panel panel-default">
        <div class="panel-heading">Impairement Test </div>

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
                                $show_next = [];
                            @endphp
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
                                                $show_next[] = true;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.update', ['id'=> $asset->id]) }}">Update Select Discount Rate </a>
                                        @else
                                            @php
                                                $show_next[] = false;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.create', ['id'=> $asset->id]) }}">Add Select Discount Rate</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
                                $show_next = [];
                            @endphp
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
                                                $show_next[] = true;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.update', ['id'=> $asset->id]) }}">Update Select Low Value </a>
                                        @else
                                            @php
                                                $show_next[] = false;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.discountrate.create', ['id'=> $asset->id]) }}">Add Select Low Value</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
                    <div class="form-group">

                        <div class="col-md-6 col-md-offset-4">
                              <a href="{{ route('addlease.lowvalue.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
                              @if(!in_array(false, $show_next))
                                    <a href="#" class="btn btn-primary">Next</a>
                              @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
