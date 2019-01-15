@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Add New Lease | Lease Balance As On 31 Dec 2018 </div>

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
                                        @if($asset->leaseBalanceAsOnDec)
                                            @php
                                                $show_next[] = true;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.balanceasondec.update', ['id'=> $asset->id]) }}">Update Lease Balance </a>
                                        @else
                                            @php
                                                $show_next[] = false;
                                            @endphp
                                            <a class="btn btn-sm btn-info" href="{{ route('addlease.balanceasondec.create', ['id'=> $asset->id]) }}">Add Lease Balance</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @else 
                                <tr>
                                    <td colspan="5">
                                        <center>No Records Exists. Please click on the next button to go to next step.</center></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="form-group">

                        <div class="col-md-6 col-md-offset-4">
                              <a href="{{ route('addlease.discountrate.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
                              @if(!in_array(false, $show_next))
                                    <a href="{{ route('addlease.initialdirectcost.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
                              @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
