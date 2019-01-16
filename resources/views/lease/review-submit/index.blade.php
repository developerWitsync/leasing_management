@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Review & Submit</div>

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
            <form class="form-horizontal" method="POST" action="{{ route('addlease.reviewsubmit.index', ['id' => $lease->id]) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="panel panel-info">
            <div class="panel-heading">Section A: Lessor Details</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Lessor Name</th>
                            @if(isset($lease->lessor_name))
                            <td>{{ $lease->lessor_name }}</td>
                            @else
                            <td>-</td>
                            @endif
                        </tr><tr>
                            <th>Lease Type Classification</th>
                            @if(isset($lease->leaseType->title))
                            <td>{{ $lease->leaseType->title }}</td>
                            @else
                            <td>-</td>
                            @endif
                        </tr><tr>
                            <th>Lease Contract Currency</th>
                            @if(isset($lease->lease_contract_id))
                            <td>{{ $lease->lease_contract_id }}</td>
                            @else
                            <td>-</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Action</th>
                            <td> <a href="{{ route('add-new-lease.index', ['id' => $lease->id]) }}"><div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                   <button type="Submit" class="btn btn-success">
                                       Edit
                                    </button>
                                    </div>
                            </div></a></td>
                        </tr>
                        </thead>
                    </table>
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
                            @foreach($assets as $key=>$asset)
                          <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if(isset($asset->uuid))
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->category->title))
                                    <td>
                                        {{ $asset->category->title }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->subcategory->title))
                                    <td>
                                        {{ $asset->subcategory->title }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->name))
                                    <td>
                                        {{ $asset->name }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->similar_asset_items))
                                    <td>
                                        {{ $asset->similar_asset_items }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    <td><a href="{{ route('addlease.leaseasset.index', ['id' => $lease->id]) }}"><button data-toggle='tooltip' data-placement='top' title='Edit Lease Details' type="button" class="btn btn-sm  btn-success edit_lease_detail"><i class="fa fa-pencil-square-o fa-lg"></td></i></a>
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
                            @foreach($assets as $key=>$asset)
                          <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if(isset($asset->uuid))
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->name))
                                    <td>
                                        {{ $asset->name }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->country->name))
                                    <td>
                                        {{ $asset->country->name }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->specificUse->title))
                                    <td>
                                        {{ $asset->specificUse->title }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->expectedLife->years))
                                    <td>
                                        {{ $asset->expectedLife->years }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif 
                                     <td><a href="{{ route('addlease.leaseasset.completedetails', ['lease' => $lease->id, 'asset' => $asset->id]) }}"><button data-toggle='tooltip' data-placement='top' title='Edit Lease Details' type="button" class="btn btn-sm  btn-success edit_lease_detail"><i class="fa fa-pencil-square-o fa-lg"></td></i></a>
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
                            <th>Start Date of Lease Payment/Accrual Period</th>
                            <th>Lease End Date, Non-Cancellable Period</th>
                            <th>Lease Term(in Months & Years)</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($assets as $key=>$asset)
                          <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if(isset($asset->uuid))
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->lease_start_date))
                                    <td>
                                        {{date('d-m-Y', strtotime($asset->lease_start_date))}}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->lease_free_period))
                                    <td>
                                        {{ $asset->lease_free_period }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->accural_period))
                                    <td>
                                        {{date('d-m-Y', strtotime($asset->accural_period))}}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->lease_end_date))
                                    <td>
                                        {{date('d-m-Y', strtotime($asset->lease_end_date))}}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->lease_term))
                                    <td>
                                        {{ $asset->lease_term }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    <td><a href="{{ route('addlease.leaseasset.completedetails', ['lease' => $lease->id, 'asset' => $asset->id]) }}"><button data-toggle='tooltip' data-placement='top' title='Edit Lease Details' type="button" class="btn btn-sm  btn-success edit_lease_detail"><i class="fa fa-pencil-square-o fa-lg"></td></i></a>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
             <div class="panel panel-info">
            <div class="panel-heading">Section E:  Lease Payments</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                            @foreach($assets as $key=>$asset)
                        <tr>
                            <th>Unique ULA Code</th>
                            @if(isset($asset->uuid))
                            <td style="width: 10%">{{ $asset->uuid}}</td>
                            @else
                            <td>-</td>
                            @endif
                        </tr>
                        <tr>
                        <th>Name of Lease Payment</th>
                        @if(isset($asset->payments[$key]->name))
                        <td>{{$asset->payments[$key]->name}}</td>
                        @else
                        <td>-</td>
                        @endif
                    </tr>  
                    <tr>
                        <th>Type of Lease Payment</th>
                        @if(isset($asset->payments[$key]->paymentType->title))
                        <td>{{$asset->payments[$key]->paymentType->title}}</td>
                        @else
                        <td>-</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Nature of Lease Payment</th>
                        @if(isset($asset->payments[$key]->paymentNature->title))
                        <td>{{$asset->payments[$key]->paymentNature->title}}</td>
                        @else
                        <td>-</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Lease Payment Interval</th>
                        @if(isset($asset->payments[$key]->paymentFrequency->title))
                        <td>{{$asset->payments[$key]->paymentFrequency->title}}</td>
                        @else
                        <td>-</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Lease Payment Payout Time</th>
                        @if(isset($asset->payments[$key]->paymentInterval->title))
                         <td>{{$asset->payments[$key]->paymentInterval->title}}</td>
                         @else
                        <td>-</td>
                        @endif
                    </tr>
                    <tr>
                        <th>First Lease Payment Start Date</th>
                        @if(isset($asset->payments[$key]->first_payment_start_date))
                         <td>{{date('d-m-Y', strtotime($asset->payments[$key]->first_payment_start_date))}}</td>
                         @else
                        <td>-</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Last Lease Payment End Date</th>
                        @if(isset($asset->payments[$key]->last_payment_end_date))
                        <td>{{date('d-m-Y', strtotime($asset->payments[$key]->last_payment_end_date))}}</td>
                        @else
                        <td>-</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Lease Payment Base</th>
                        @if(isset($asset->payments[$key]->last_payment_end_date))
                        <td>{{date('d-m-Y', strtotime($asset->payments[$key]->last_payment_end_date))}}</td>
                        @else
                        <td>-</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Total Lease Amount Per Interval</th>
                        @if(isset($asset->payments[$key]->total_amount_per_interval))
                        <td>{{$asset->payments[$key]->total_amount_per_interval}}</td>
                        @else
                        <td>-</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Total Undiscounted Lease Payments</th>
                        @if(isset($asset->payments[$key]->last_payment_end_date))
                        <td>{{date('d-m-Y', strtotime($asset->payments[$key]->last_payment_end_date))}}</td>
                        @else
                        <td>-</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Action</th>
                          <td> <a href="{{ route('lease.payments.add', ['lease_id' => $lease->id, 'asset_id' => $asset->id, 'payment_id'=> $asset->payments[$key]->id]) }}"><div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                   <button type="Submit" class="btn btn-success">
                                       Edit
                                    </button>
                                    </div>
                            </div></a></td>
                    </tr>
                        </thead>
                            @endforeach
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
                            @foreach($assets as $key=>$asset)
                          <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if(isset($asset->uuid))
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->fairMarketValue->total_units))
                                    <td>
                                        {{ $asset->fairMarketValue->total_units }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->residualGuranteeValue->total_residual_gurantee_value))
                                    <td>
                                        {{ $asset->residualGuranteeValue->total_residual_gurantee_value }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->terminationOption->termination_penalty))
                                    <td>
                                        {{ $asset->terminationOption->termination_penalty }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->purchaseOption->purchase_price))
                                    <td>
                                        {{ $asset->purchaseOption->purchase_price }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if(isset($asset->purchaseOption->expected_purchase_date))
                                    <td>{{date('d-m-Y', strtotime($asset->purchaseOption->expected_purchase_date)) }}
                                    </td>
                                    @else
                                    <td>-</td>
                                    @endif
                                   <td><a href="{{ route('addlease.residual.index', ['lease' => $lease->id]) }}"><button data-toggle='tooltip' data-placement='top' title='Edit Lease Details' type="button" class="btn btn-sm  btn-success edit_lease_detail"><i class="fa fa-pencil-square-o fa-lg"></td></i></a> 
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
                            @foreach($assets as $key=>$asset)
                          <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if(isset($asset->uuid))
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    @else
                                    <td>-</td>
                                     @endif
                                    @if($asset->leaseDurationClassified->getLeaseClassification->title)
                                    <td>
                                        {{ $asset->leaseDurationClassified->getLeaseClassification->title}}
                                    </td>
                                    @else
                                    <td>-</td>
                                     @endif
                                     @if(isset($asset->leaseSelectLowValue->is_classify_under_low_value))
                                    @if($asset->leaseSelectLowValue->is_classify_under_low_value =="yes")
                                    <td>Low Value</td>
                                    @endif
                                    @if($asset->leaseSelectLowValue->is_classify_under_low_value =="no")
                                    <td>High Value</td>
                                    @endif
                                    @else
                                    <td>-</td>
                                     @endif
                                    @if(isset($asset->leaseSelectDiscountRate->discount_rate_to_use))
                                     <td>
                                        {{ $asset->leaseSelectDiscountRate->discount_rate_to_use }}
                                    </td>
                                    @else
                                    <td>-</td>
                                     @endif
                                      @if(isset($asset->initialDirectCost->total_initial_direct_cost))
                                     <td>
                                        {{ $asset->initialDirectCost->total_initial_direct_cost }}
                                    </td>
                                    @else
                                    <td>-</td>
                                     @endif
                                    @if(isset($asset->leaseIncentiveCost->total_lease_incentives))
                                     <td>
                                        {{ $asset->leaseIncentiveCost->total_lease_incentives }}
                                    </td>
                                    @else
                                    <td>-</td>
                                     @endif
                                     <td><a href="{{ route('addlease.durationclassified.index', ['lease' => $lease->id]) }}"><button data-toggle='tooltip' data-placement='top' title='Edit Lease Details' type="button" class="btn btn-sm  btn-success edit_lease_detail"><i class="fa fa-pencil-square-o fa-lg"></td></i></a> 
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
                            @foreach($assets as $key=>$asset)
                          <tr>
                                    <td>{{ $key + 1 }}</td>
                                    @if(isset($asset->uuid))
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    @else
                                    <td>-</td>
                                     @endif
                                    @if(isset($asset->name))
                                    <td>
                                        {{ $asset->name }}
                                    </td>
                                    @else
                                    <td>-</td>
                                     @endif
                                    @if(isset($asset->subcategory->title ))
                                    <td>
                                        {{ $asset->subcategory->title }}
                                    </td>
                                    @else
                                    <td>-</td>
                                     @endif
                                    @if(isset($asset->subcategory->title ))
                                     <td>
                                        {{ $asset->subcategory->title }}
                                    </td>
                                    @else
                                    <td>-</td>
                                     @endif
                                    <td><button data-toggle='tooltip' data-placement='top' title='Edit Lease Details' type="button" class="btn btn-sm  btn-success edit_lease_detail"><i class="fa fa-pencil-square-o fa-lg"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
                    <div class="form-group">

                        <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success">
                                        Save As Draft
                                    </button>
                                    <!-- <a href="{{route('addlease.reviewsubmit.index', ['id'=>$lease->id])}}" class="btn btn-success">Save As Draft</a> -->
                                    <a href="#" class="btn btn-primary">Print</a>
                        </div>

                    </div>
              </form>  
          </div>
      </div>
@endsection
