@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Add New Lease | Review & Submit</div>

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
                        <div class="panel-heading">Section A: Lessor Details</div>
                  <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
               
                @if($reporting_currency_settings->is_foreign_transaction_involved == 'yes' || $reporting_currency_settings->is_foreign_transaction_involved == 'no' )
               
                {{--@include('lease._menubar')--}}

               <div class="tab-content" style="padding: 0px;">
                    <div role="tabpanel" class="tab-pane active">
                        @if($lease->id)
                            <form id="add-new-lease-form" class="form-horizontal" method="POST" action="{{ route('add-new-lease.index.update', ['id' => $lease->id]) }}" enctype="multipart/form-data">
                        @else
                            <form id="add-new-lease-form" class="form-horizontal" method="POST" action="{{ route('add-new-lease.index.save') }}" enctype="multipart/form-data">
                        @endif
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('lessor_name') ? ' has-error' : '' }} required">
                                <label for="lessor_name" class="col-md-4 control-label">Lessor Name</label>
                                <div class="col-md-6">
                                        <input id="lessor_name" type="text" placeholder="Lessor Name" class="form-control" name="lessor_name" value="{{ old('lessor_name',$lease->lessor_name) }}" >
                                    @if ($errors->has('lessor_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lessor_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lease_type_id') ? ' has-error' : '' }} required">
                                <label for="lease_type_id" class="col-md-4 control-label">Lease Type Classification</label>
                                <div class="col-md-6">
                                   <select name="lease_type_id" class="form-control" id="lease_type_id">
                                        <option value="">Please Type Classification</option>
                                         @php $i =1 @endphp
                                         @foreach($contract_classifications as $classification)
                                         <option class="cla-{{$i}}" value="{{ $classification->id }}" @if(old('lease_type_id',$lease->lease_type_id) == $classification->id) selected="selected" @endif>
                                            {{ $classification->title }} </option>
                                           @php $i++ @endphp
                                        @endforeach
                                    </select>
                                    @if ($errors->has('lease_type_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_type_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($reporting_currency_settings->is_foreign_transaction_involved == "yes")
                                  <div class="form-group{{ $errors->has('lease_contract_id') ? ' has-error' : '' }} required">
                                        <label for="lease_contract_id" class="col-md-4 control-label">Lease Contract Currency</label>
                                        <div class="col-md-6">
                                              <select name="lease_contract_id" class="form-control">
                                                <option value="">Please Type Lease Contract</option>

                                                @foreach($reporting_foreign_currency_transaction_settings as $currencies)
                                                 <option value="{{ $currencies->foreign_exchange_currency }}" @if(old('lease_contract_id', $lease->lease_contract_id) == $currencies->foreign_exchange_currency) selected="selected" @endif>
                                                    {{ $currencies->foreign_exchange_currency }}{{ '('.$currencies->base_currency.')' }}</option>
                                                     @endforeach
                                            </select>
                                            @if ($errors->has('lease_contract_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('lease_contract_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                  </div>
                            @endif

                            @if($reporting_currency_settings->is_foreign_transaction_involved == "no") 
                                    <div class="form-group{{ $errors->has('lease_contract_id') ? ' has-error' : '' }} required">
                                            <label for="lease_contract_id" class="col-md-4 control-label">Lease Contract Currency</label>
                                            <div class="col-md-6">
                                                   <select name="lease_contract_id" class="form-control">
                                                    <option value="">Please Type Lease Contract</option>
                                                     <option value="{{ $reporting_currency_settings->currency_for_lease_reports }}" @if(old('lease_contract_id', $lease->lease_contract_id) == $reporting_currency_settings->currency_for_lease_reports) selected="selected" @endif >
                                                        {{ $reporting_currency_settings->currency_for_lease_reports }}</option>
                                                       
                                                </select>
                                                @if ($errors->has('lease_contract_id'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('lease_contract_id') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                     </div>
                            @endif


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                   <button type="submit" class="btn btn-success">
                                       Edit
                                    </button>
                                    </div>
                            </div>

                        </form>
                    </div>
                </div>
                 @else
                <a href="{{route('settings.currencies')}}"><div class="alert alert-danger">Please change the foreign currency settings</div></a>
             @endif
            </div>
                </div>

            <div class="panel panel-info">
            <div class="panel-heading">Section B: Underlying Lease Assets</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Underlying Lease Asset Category</th>
                            <th>Underlying Lease Asset Classification</th>
                            <th>Name of the Underlying Lease Asset</th>
                            <th>Number of Units of Lease Assets of Similar Characteristics</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                           <!--  @php
                                $show_next = [];
                            @endphp -->
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
                                 <!--  <td>
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
            <div class="panel panel-info">
            <div class="panel-heading">Section C: Basic Details of Underlying Lease Assets</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Country of the Lease Asset</th>
                            <th>Place</th>
                            <th>Specific Use of the Lease Asset</th>
                            <th>Expected Remaining Useful Life of the Underlying</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <!-- @php
                                $show_next = [];
                            @endphp -->
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
             <div class="panel panel-info">
            <div class="panel-heading">Section D: Lease Start & End Dates</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Lease Start Date</th>
                            <th>Initial Lease Free Period, If any</th>
                            <th>Start Date of Lease Payment / Accrual Period</th>
                            <th>Lease End Date, Non-Cancellable Period</th>
                            <th>Lease Term (in Months & Years)</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $show_next = [];
                            @endphp
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
             <div class="panel panel-info">
            <div class="panel-heading">Section E:  Lease Payments</div>
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
                           <!--  @php
                                $show_next = [];
                            @endphp -->
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
                                        <!-- {{ $asset->category_id->title }} -->
                                    </td>
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
              <div class="panel panel-info">
            <div class="panel-heading">Section F:  Underlying Lease Assets - Termination, or Purchase Options</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Total FMV</th>
                            <th>Total Residual Guarantee Value</th>
                            <th>Termination Penalty</th>
                            <th>Anticipated Purchase Price</th>
                            <th>Expected Purchse Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <!-- @php
                                $show_next = [];
                            @endphp -->
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
            <div class="panel panel-info">
            <div class="panel-heading">Section G:  Underlying Lease Assets Classified</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Lease Classified</th>
                            <th>Classify under Low Value Lease Asset</th>
                            <th>Discount Rates</th>
                            <th>Total Initial Direct Cost</th>
                            <th>Total Lease Incentive</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <!-- @php
                                $show_next = [];
                            @endphp -->
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
              <div class="panel panel-info">
            <div class="panel-heading">Section H:  Underlying Lease Assets Valuation</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Present Value of Lease Liability</th>
                            <th>Value of a Lease Asset</th>
                            <th>Adjustment to Equity</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <!-- @php
                                $show_next = [];
                            @endphp -->
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
@endsection
