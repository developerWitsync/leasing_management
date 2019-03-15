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

            @include('lease._subsequent_details')


            <div class="panel panel-info">
                <div class="panel-heading">Section A: Lessor Details</div>
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Lessor</th>
                        <th>Lease Classification</th>
                        <th>Lease Currency</th>
                        <th>Action</th>

                    </tr>
                    <tr>
                        <td> 1</td>
                        @if(isset($lease->lessor_name))
                            <td>{{ $lease->lessor_name }}</td>
                        @endif
                        @if(isset($lease->leaseType->title))
                            <td>{{ $lease->leaseType->title }}</td>
                        @endif
                        @if(isset($lease->lease_contract_id))
                            <td>{{ $lease->lease_contract_id }}</td>
                        @endif
                        <td>
                            <a href="{{ route('add-new-lease.index', ['id' => $lease->id]) }}">
                                <button data-toggle='tooltip' data-placement='top' title='Edit Lessor Details'
                                        type="button" class="btn btn-sm  btn-success"><i
                                            class="fa fa-pencil-square-o fa-lg"></td>
                        </i></a>
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
                        <th>LA Category</th>
                        <th>LA Classification</th>
                        <th>Lease Asset</th>
                        <th>LA of Similar Characteristics</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($assets) >0)
                        @foreach($assets as $key=>$asset)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                               
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
                                <td><a href="{{ route('addlease.leaseasset.index', ['id' => $lease->id]) }}">
                                        <button data-toggle='tooltip' data-placement='top' title='Edit Lease Details'
                                                type="button" class="btn btn-sm  btn-success edit_lease_detail"><i
                                                    class="fa fa-pencil-square-o fa-lg"></td>
                                </i></a>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">Section C: Basic Details of Underlying Lease Assets</div>
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>LA Country</th>
                        <th>Place</th>
                        <th>Specific Use of the Lease Asset</th>
                        <th>Expected Remaining Useful Life of the Underlying</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($assets) >0)
                        @foreach($assets as $key=>$asset)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                
                                @if(isset($asset->country->name))
                                    <td>
                                        {{ $asset->country->name }}
                                    </td>
                                @else
                                    <td>-</td>
                                @endif
                                @if(isset($asset->location))
                                    <td>
                                        {{ $asset->location }}
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
                                <td>
                                    <a href="{{ route('addlease.leaseasset.completedetails', ['lease' => $lease->id, 'asset' => $asset->id]) }}">
                                        <button data-toggle='tooltip' data-placement='top' title='Edit Lease Details'
                                                type="button" class="btn btn-sm  btn-success edit_lease_detail"><i
                                                    class="fa fa-pencil-square-o fa-lg"></td>
                                </i></a>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">Section D: Lease Start & End Dates</div>
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Sr. No.</th>
                     
                        <th>Lease Start Date</th>
                        <th>Initial Lease Free Period, If any</th>
                        <th>Start Date of Lease Payment/Accrual Period</th>
                        <th>Lease End Date, Non-Cancellable Period</th>
                        <th>Lease Term(in Months & Years)</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($assets) >0)
                        @foreach($assets as $key=>$asset)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                               
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
                                <td>
                                    <a href="{{ route('addlease.leaseasset.completedetails', ['lease' => $lease->id, 'asset' => $asset->id]) }}">
                                        <button data-toggle='tooltip' data-placement='top' title='Edit Lease Details'
                                                type="button" class="btn btn-sm  btn-success edit_lease_detail"><i
                                                    class="fa fa-pencil-square-o fa-lg"></td>
                                </i></a>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">Section E: Lease Payments</div>
                <table class="table table-bordered table-responsive">
                    <thead>
                        <th>Lease Payment Name</th>
                        <th>Lease Payment Type</th>
                        <th>Lease Payment Nature</th>
                        <th>Lease Payment Interval</th>
                        <th>Lease Payment Payout Time</th>
                        <th>First Lease Payment Start Date</th>
                        <th>Last Lease Payment End Date</th>
                        <th>Lease Payment Base</th>
                        <th>Total Lease Amount Per Interval</th>
                        <th>Total Undiscounted Lease Payments</th>
                        <th>Action</th>
                    </thead>
                    <tbody>

                        @foreach($asset->payments as $payment)
                            <tr>
                            <td>{{$payment->name}}</td>
                            <td>{{$payment->paymentType->title}}</td>
                            <td>{{$payment->paymentNature->title}}</td>
                            <td>{{ ($payment->payment_interval)?$payment->paymentFrequency->title:"N/A"}}</td>
                            <td>{{ ($payment->payout_time)?$payment->paymentInterval->title:"N/A"}}</td>
                            <td>{{ ($payment->first_payment_start_date)?date('d-m-Y', strtotime($payment->first_payment_start_date)) : "N/A" }}</td>
                            <td>{{ ($payment->last_payment_end_date) ? date('d-m-Y', strtotime($payment->last_payment_end_date)) : "N/A" }}</td>
                            <td>{{ ($payment->first_payment_start_date) ? date('d-m-Y', strtotime($payment->first_payment_start_date)) : "N/A" }}</td>
                            <td>{{$payment->total_amount_per_interval}}</td>
                            <td>{{$payment->total_amount_per_interval}}</td>
                            <td>
                                <a href="{{ route('lease.payments.add', ['lease_id' => $lease->id, 'asset_id' => $asset->id, 'payment_id'=> $payment->id]) }}">
                                    <button data-toggle='tooltip' data-placement='top' title='Edit Lease Payments'
                                            type="button" class="btn btn-sm  btn-success"><i
                                                class="fa fa-pencil-square-o fa-lg"></i>
                                    </button>
                                </a>
                            </td>
                            <tr>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">Section F: Underlying Lease Assets - Termination, or Purchase Options</div>
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Total FMV</th>
                        <th>Total Residual Guarantee Value</th>
                        <th> Residual Guarantee Value Description</th>
                        <th>Termination Penalty</th>
                        <th>Anticipated Purchase Price</th>
                        <th>Expected Purchse Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($assets) >0)
                        @foreach($assets as $key=>$asset)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                               
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

                            @if(isset($asset->residualGuranteeValue->other_desc))
                            <td>{{ $asset->residualGuranteeValue->other_desc }}</td>

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
                                <td>
                                    <a href="{{ route('addlease.residual.index', ['lease' => $lease->id]) }}">
                                        <button data-toggle='tooltip' data-placement='top' title='Edit Lease Details'
                                                type="button" class="btn btn-sm  btn-success edit_lease_detail"><i
                                                    class="fa fa-pencil-square-o fa-lg"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">Section G: Underlying Lease Assets Classified</div>
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Lease Classified</th>
                        <th>Classify under Low Value LA</th>
                        <th>Discount Rates</th>
                        <th>Total Initial Direct Cost</th>
                        <th>Total Lease Incentive</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($assets) >0)
                        @foreach($assets as $key=>$asset)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                
                                @if(isset($asset->leaseDurationClassified->getLeaseClassification->title))
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
                                <td>
                                    <a href="{{ route('addlease.durationclassified.index', ['lease' => $lease->id]) }}">
                                        <button data-toggle='tooltip' data-placement='top' title='Edit Lease Details'
                                                type="button" class="btn btn-sm  btn-success edit_lease_detail">
                                            <i class="fa fa-pencil-square-o fa-lg"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">Section H: Underlying Lease Assets Valuation</div>
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>PV of Lease Liability</th>
                        <th>Value of a Lease Asset</th>
                        <th>Adjustment to Equity</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($assets) >0)
                        @foreach($assets as $key=>$asset)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                               

                                <td>{{ is_null($asset->lease_liablity_value)?'-':$asset->lease_liablity_value }}</td>
                                <td>{{ is_null($asset->value_of_lease_asset)?'-':$asset->value_of_lease_asset }}</td>
                                <td>{{ $asset->lease_liablity_value - $asset->value_of_lease_asset }}</td>
                                <td>
                                    <a href="{{ route('addlease.leasevaluation.index', ['lease' => $lease->id]) }}">
                                        <button data-toggle='tooltip' data-placement='top' title='Edit Lease Details'
                                                type="button" class="btn btn-sm  btn-success edit_lease_detail"><i
                                                    class="fa fa-pencil-square-o fa-lg"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>



            <div class="form-group btnMainBx clearfix">
                <div class="col-md-4 col-sm-4 btn-backnextBx">

                 <a href="{{ route('add-new-lease.index',['id' => $lease->id]) }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
                </div>
                 <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">
                     <a href="#" class="btn btn-primary">{{ env('PRINT_LABEL') }}</a>
                </div>
                
                <div class="col-md-4 col-sm-4 btnsubmitBx alignright">
                    <form style="display: inline-block;"
                          action="{{route('addlease.reviewsubmit.submit', ['id'=>$lease->id])}}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
