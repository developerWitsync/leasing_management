@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        {{--<div class="panel-heading">Lease Classifications Settings</div>--}}

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @include('settings._menubar')

            <div class="">
                <div role="tabpanel" class="tab-pane active">
                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Percentage Rate Types</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($rates as $key =>$rate)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $rate->title }}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Lease Contract Classification</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($contract_classifications as $key =>$classification)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $classification->title }}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Use of Lease Asset</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($lease_asset_use as $key =>$value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $value->title }}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Components of Lease Payments</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($lease_payment_component as $key =>$value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $value->title }}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                                {{--Percentage Rate Types--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Nature of Lease Payments</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($lease_payment_nature as $key =>$value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $value->title }}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}


                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Basis of Variable Lease Payment
                            <span>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary pull-right add_more"
                                       data-form="add_more_form_lease_basis">Add More</a>
                                </span>
                        </div>
                        <div class="panel-body settingTble">
                            <table class="table table-condensed lease_payment_basis_table">
                                <thead>
                                <tr>
                                    <th width="80px">Sr No.</th>
                                    <th>Title</th>
                                    <th width="120px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($lease_payment_basis as $key =>$value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="title">
                                            {{ $value->title }}
                                        </td>
                                        <td>

                                            <a data-href="{{ route('settings.leaseclassification.editleasepaymentbasis', ['id' => $value->id]) }}"
                                               href="javascript:;" class="btn btn-sm btn-success edit_table_setting">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>

                                            <a data-href="{{ route('settings.leaseclassification.deleteleasepaymentbasis', ['id' => $value->id]) }}"
                                               href="javascript:;" class="btn btn-sm btn-danger delete_settings"><i
                                                        class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style=" {{ $errors->has('lease_basis_title') ? ' has-error' : 'display: none' }}"
                                    class="add_more_form_lease_basis">
                                    <td>{{ count($lease_payment_basis) + 1 }}</td>
                                    <td>
                                        <form action="{{ route('settings.leaseclassification.addleasepaymentbasis') }}"
                                              method="POST" class="add_more_form_lease_basis_form">
                                            {{ csrf_field() }}
                                            <div class="form-group{{ $errors->has('lease_basis_title') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ old('lease_basis_title') }}"
                                                       name="lease_basis_title" placeholder="Title"
                                                       class="form-control {{ $errors->has('lease_basis_title') ? ' has-error' : '' }}"/>
                                                @if ($errors->has('lease_basis_title'))
                                                    <span class="help-block">
                                                                <strong>{{ $errors->first('lease_basis_title') }}</strong>
                                                            </span>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                                onclick="javascript:$('.add_more_form_lease_basis_form').submit();"
                                                class="btn btn-sm btn-success" title="Save"><i
                                                    class="fa fa-check-square"></i></button>
                                        <a href="javascript:;" class="btn btn-sm btn-danger add_more"
                                           data-form="add_more_form_lease_basis" title="Cancel"><i
                                                    class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>


                    {{--<div class="panel panel-info">
                        <div class="panel-heading">
                            Number of Underlying Lease Assets
                            <span>
                                    <a href="javascript:void(0);"
                                       class="btn btn-sm btn-primary pull-right add_more disabled"
                                       data-form="add_more_no_of_underlying_asset">Add More</a>
                                </span>
                        </div>
                        <div class="panel-body settingTble">
                            <table class="table table-condensed lease_payment_basis_table">
                                <thead>
                                <tr>
                                    <th width="80px">Sr No.</th>
                                    <th>Number</th>
                                    <th width="120px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($number_of_underlying_assets_settings as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="title">
                                            {{ $value->number }}
                                        </td>
                                        <td>
                                            @if($value->number != "1")
                                                <a data-href="{{ route('settings.leaseclassification.editleaseassetnumber', ['id' => $value->id]) }}"
                                                   href="javascript:;"
                                                   class="btn btn-sm btn-success edit_table_setting">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>

                                                <a data-href="{{ route('settings.leaseclassification.deleteleaseassetnumber', ['id' => $value->id]) }}"
                                                   href="javascript:;" class="btn btn-sm btn-danger delete_settings"><i
                                                            class="fa fa-trash-o"></i></a>
                                            @else
                                                &nbsp;
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style=" {{ $errors->has('lease_asset_number') ? ' has-error' : 'display: none' }}"
                                    class="add_more_no_of_underlying_asset">
                                    <td>{{ count($number_of_underlying_assets_settings) + 1 }}</td>
                                    <td>
                                        <form action="{{ route('settings.leaseclassification.addleaseassetnumber') }}"
                                              method="POST" class="add_more_no_of_underlying_asset_form">
                                            {{ csrf_field() }}
                                            <div class="form-group{{ $errors->has('lease_asset_number') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ old('lease_asset_number') }}"
                                                       name="lease_asset_number" placeholder="Number"
                                                       class="form-control {{ $errors->has('lease_asset_number') ? ' has-error' : '' }}"/>
                                                @if ($errors->has('lease_asset_number'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('lease_asset_number') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                                onclick="javascript:$('.add_more_no_of_underlying_asset_form').submit();"
                                                class="btn btn-sm btn-success" title="Save"><i
                                                    class="fa fa-check-square"></i></button>
                                        <a href="javascript:;" class="btn btn-sm btn-danger add_more"
                                           data-form="add_more_no_of_underlying_asset" title="Cancel"><i
                                                    class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div> --}}

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Number of Lease Assets of Similar Characteristics
                            <span>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary pull-right add_more"
                                       data-form="add_more_no_of_lease_asset_similar_charac">Add More</a>
                                </span>
                        </div>
                        <div class="panel-body settingTble">
                            <table class="table table-condensed">
                                <thead>
                                <tr>
                                    <th width="80px">Sr No.</th>
                                    <th>Number</th>
                                    <th width="120px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($la_similar_charac_number as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="title">
                                            {{ $value->number }}
                                        </td>
                                        <td>
                                            @if($value->number != "1")
                                                <a data-href="{{ route('settings.leaseclassification.editleasesimilarcharac', ['id' => $value->id]) }}"
                                                   href="javascript:;"
                                                   class="btn btn-sm btn-success edit_table_setting">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>

                                                <a data-href="{{ route('settings.leaseclassification.deleteleasesimilarcharac', ['id' => $value->id]) }}"
                                                   href="javascript:;" class="btn btn-sm btn-danger delete_settings"><i
                                                            class="fa fa-trash-o"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style=" {{ $errors->has('lease_similar_charac_number') ? ' has-error' : 'display: none' }}"
                                    class="add_more_no_of_lease_asset_similar_charac">
                                    <td>{{ count($la_similar_charac_number) + 1 }}</td>
                                    <td>
                                        <form action="{{ route('settings.leaseclassification.addleasesimilarcharac') }}"
                                              method="POST" class="add_more_no_of_lease_asset_similar_charac_form">
                                            {{ csrf_field() }}
                                            <div class="form-group{{ $errors->has('lease_similar_charac_number') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ old('lease_similar_charac_number') }}"
                                                       name="lease_similar_charac_number" placeholder="Number"
                                                       class="form-control {{ $errors->has('lease_similar_charac_number') ? ' has-error' : '' }}"/>
                                                @if ($errors->has('lease_similar_charac_number'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('lease_similar_charac_number') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                                onclick="javascript:$('.add_more_no_of_lease_asset_similar_charac_form').submit();"
                                                class="btn btn-sm btn-success" title="Save"><i
                                                    class="fa fa-check-square"></i></button>
                                        <a href="javascript:;" class="btn btn-sm btn-danger add_more"
                                           data-form="add_more_no_of_lease_asset_similar_charac" title="Cancel"><i
                                                    class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Contract Escalation Basis</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($contract_escalation_basis as $key =>$value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $value->title }}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Lease Contract Duration</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Time Range</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($lease_contract as $key =>$value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $value->month_range_description }}</td>--}}
                                        {{--<td>{{ $value->title }}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Leases Excluded from Transitional Valuation</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Purpose</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($lease_excluded_from_transitional_valuation  as $key =>$value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>--}}
                                            {{--@if($value->value_for == 'lease_asset_level')--}}
                                                {{--Underlying Lease Asset Level--}}
                                            {{--@else--}}
                                                {{--Lease Payments--}}
                                            {{--@endif--}}
                                        {{--</td>--}}
                                        {{--<td>{{ $value->title }}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--@foreach($lease_accounting_treatment as $year=>$treatments)--}}
                        {{--<div class="panel panel-info">--}}
                            {{--<div class="panel-heading">Lease Accounting Treatment Upto {{ $year }}</div>--}}
                            {{--<div class="panel-body settingTble">--}}
                                {{--<table class="table table-condensed">--}}
                                    {{--<thead>--}}
                                    {{--<tr>--}}
                                        {{--<th width="80px">Sr No.</th>--}}
                                        {{--<th>Title</th>--}}
                                    {{--</tr>--}}
                                    {{--</thead>--}}
                                    {{--<tbody>--}}
                                    {{--@foreach($treatments  as $key => $value)--}}
                                        {{--<tr>--}}
                                            {{--<td>{{ $key + 1 }}</td>--}}
                                            {{--<td>{{ $value['title']}}</td>--}}
                                        {{--</tr>--}}
                                    {{--@endforeach--}}
                                    {{--</tbody>--}}
                                {{--</table>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--@endforeach--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">--}}
                            {{--Number of Lease Payments--}}
                            {{--<span>--}}
                            {{--<a href="javascript:void(0);" class="btn btn-sm btn-primary pull-right add_more" data-form="add_more_no_of_lease_payments">Add More</a>--}}
                            {{--</span>--}}
                        {{--</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Number</th>--}}
                                    {{--<th width="120px">Action</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($number_of_lease_payments as $key => $value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td class="title">--}}
                                            {{--{{ $value->number }}--}}
                                        {{--</td>--}}
                                        {{--<td>--}}

                                        {{--<a data-href="{{ route('settings.leaseclassification.editleasepaymentsnumber', ['id' => $value->id]) }}" href="javascript:;" class="btn btn-sm btn-success edit_table_setting">--}}
                                        {{--<i class="fa fa-pencil-square-o"></i>--}}
                                        {{--</a>--}}

                                        {{--<a data-href="{{ route('settings.leaseclassification.deleteleasepaymentsnumber', ['id' => $value->id]) }}" href="javascript:;" class="btn btn-sm btn-danger delete_settings"><i class="fa fa-trash-o"></i></a>--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--<tr style=" {{ $errors->has('lease_payments_no') ? ' has-error' : 'display: none' }}"--}}
                                    {{--class="add_more_no_of_lease_payments">--}}
                                    {{--<td>{{ count($number_of_lease_payments) + 1 }}</td>--}}
                                    {{--<td>--}}
                                        {{--<form action="{{ route('settings.leaseclassification.addleasepaymentsnumber') }}"--}}
                                              {{--method="POST" class="add_more_no_of_lease_payments_form">--}}
                                            {{--{{ csrf_field() }}--}}
                                            {{--<div class="form-group{{ $errors->has('lease_payments_no') ? ' has-error' : '' }}">--}}
                                                {{--<input type="text" value="{{ old('lease_payments_no') }}"--}}
                                                       {{--name="lease_payments_no" placeholder="Number"--}}
                                                       {{--class="form-control {{ $errors->has('lease_payments_no') ? ' has-error' : '' }}"/>--}}
                                                {{--@if ($errors->has('lease_payments_no'))--}}
                                                    {{--<span class="help-block">--}}
                                                            {{--<strong>{{ $errors->first('lease_payments_no') }}</strong>--}}
                                                        {{--</span>--}}
                                                {{--@endif--}}
                                            {{--</div>--}}
                                        {{--</form>--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--<button type="button"--}}
                                                {{--onclick="javascript:$('.add_more_no_of_lease_payments_form').submit();"--}}
                                                {{--class="btn btn-sm btn-success" title="Save"><i--}}
                                                    {{--class="fa fa-check-square"></i></button>--}}
                                        {{--<a href="javascript:;" class="btn btn-sm btn-danger add_more"--}}
                                           {{--data-form="add_more_no_of_lease_payments" title="Cancel"><i--}}
                                                    {{--class="fa fa-times"></i></a>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Lease Payment Interval</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($lease_payment_interval  as $key => $value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $value->title}}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Lease Payments Frequency</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($lease_payments_frequency  as $key => $value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $value->title}}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Escalation Percentages (% Per Annum)
                            <span>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary pull-right add_more"
                                       data-form="add_more_escalation_percentage">Add More</a>
                                </span>
                        </div>
                        <div class="panel-body settingTble">
                            <table class="table table-condensed">
                                <thead>
                                <tr>
                                    <th width="80px">Sr No.</th>
                                    <th>Number</th>
                                    <th width="120px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($escalation_percentage_settings as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="title">
                                            {{ number_format((float)$value->number , 2, '.', '')}}
                                        </td>
                                        <td>
                                            @if($value->number)
                                            <a data-href="{{ route('settings.leaseclassification.editescalationpercentagenumber', ['id' => $value->id]) }}"
                                               href="javascript:;" class="btn btn-sm btn-success edit_table_setting">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>

                                            <a data-href="{{ route('settings.leaseclassification.deleteescalationpercentagenumber', ['id' => $value->id]) }}"
                                               href="javascript:;" class="btn btn-sm btn-danger delete_settings"><i
                                                        class="fa fa-trash-o"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style=" {{ $errors->has('escalation_percentage_number') ? ' has-error' : 'display: none' }}"
                                    class="add_more_escalation_percentage">
                                    <td>{{ count($escalation_percentage_settings) + 1 }}</td>
                                    <td>
                                        <form action="{{ route('settings.leaseclassification.addescalationpercentagenumber') }}"
                                              method="POST" class="add_more_escalation_percentage_form">
                                            {{ csrf_field() }}
                                            <div class="form-group{{ $errors->has('escalation_percentage_number') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ old('escalation_percentage_number') }}"
                                                       name="escalation_percentage_number" placeholder="Number"
                                                       class="form-control {{ $errors->has('escalation_percentage_number') ? ' has-error' : '' }}"/>
                                                @if ($errors->has('escalation_percentage_number'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('escalation_percentage_number') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                                onclick="javascript:$('.add_more_escalation_percentage_form').submit();"
                                                class="btn btn-sm btn-success" title="Save"><i
                                                    class="fa fa-check-square"></i></button>
                                        <a href="javascript:;" class="btn btn-sm btn-danger add_more"
                                           data-form="add_more_escalation_percentage" title="Cancel"><i
                                                    class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>


                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Escalation Consistency Gap (in years)
                            <span>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary pull-right add_more"
                                       data-form="add_more_escalation_consistency_gap">Add More</a>
                                </span>
                        </div>
                        <div class="panel-body settingTble">
                            <table class="table table-condensed">
                                <thead>
                                <tr>
                                    <th width="80px">Sr No.</th>
                                    <th>Gap (In Years)</th>
                                    <th width="120px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($escalation_consistency_gap as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="title">
                                            @if($value->years == 1)
                                                Annual
                                            @else
                                                {{ number_format((float)$value->years , 2, '.', '')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($value->years && $value->years != 1)
                                                <a data-href="{{ route('settings.leaseclassification.editescalationconsistencygap', ['id' => $value->id]) }}"
                                                   href="javascript:;" class="btn btn-sm btn-success edit_table_setting">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>

                                                <a data-href="{{ route('settings.leaseclassification.deleteescalationconsistencygap', ['id' => $value->id]) }}"
                                                   href="javascript:;" class="btn btn-sm btn-danger delete_settings"><i
                                                            class="fa fa-trash-o"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style=" {{ $errors->has('escalation_consistency_gap') ? ' has-error' : 'display: none' }}"
                                    class="add_more_escalation_consistency_gap">
                                    <td>{{ count($escalation_consistency_gap) + 1 }}</td>
                                    <td>
                                        <form action="{{ route('settings.leaseclassification.addescalationconsistencygap') }}"
                                              method="POST" class="add_more_escalation_consistency_gap_form">
                                            {{ csrf_field() }}
                                            <div class="form-group{{ $errors->has('escalation_consistency_gap') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ old('escalation_consistency_gap') }}"
                                                       name="escalation_consistency_gap" placeholder="Years"
                                                       class="form-control {{ $errors->has('escalation_consistency_gap') ? ' has-error' : '' }}"/>
                                                @if ($errors->has('escalation_consistency_gap'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('escalation_consistency_gap') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                                onclick="javascript:$('.add_more_escalation_consistency_gap_form').submit();"
                                                class="btn btn-sm btn-success" title="Save"><i
                                                    class="fa fa-check-square"></i></button>
                                        <a href="javascript:;" class="btn btn-sm btn-danger add_more"
                                           data-form="add_more_escalation_consistency_gap" title="Cancel"><i
                                                    class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Escalation Clause Applicable on Lease Payments</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($lease_payment_escalation_clause  as $key => $value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $value->title}}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Escalation Amount Calculated On</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Title</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($escalation_amount_calculated_on  as $key => $value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $value->title}}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="panel panel-info">--}}
                        {{--<div class="panel-heading">Escalation Frequency</div>--}}
                        {{--<div class="panel-body settingTble">--}}
                            {{--<table class="table table-condensed">--}}
                                {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="80px">Sr No.</th>--}}
                                    {{--<th>Esclation Frequency</th>--}}
                                {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                {{--@foreach($escalation_frequencies  as $key => $frequency)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{ $key + 1 }}</td>--}}
                                        {{--<td>{{ $frequency->title}}</td>--}}
                                    {{--</tr>--}}
                                {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Lease Modification Reasons
                            <span>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary pull-right add_more"
                                       data-form="add_more_modification_form">Add More</a>
                                </span>
                        </div>
                        <div class="panel-body settingTble">
                            <table class="table table-condensed">
                                <thead>
                                <tr>
                                    <th width="80px">Sr No.</th>
                                    <th>Tile</th>
                                    <th width="120px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($modication_reason as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="title">
                                            {{ $value->title }}
                                        </td>
                                        <td>

                                            <a data-href="{{ route('settings.leaseclassification.editleasemodificationreason', ['id' => $value->id]) }}"
                                               href="javascript:;" class="btn btn-sm btn-success edit_table_setting">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>

                                            <a data-href="{{ route('settings.leaseclassification.deleteleasemodificationreason', ['id' => $value->id]) }}"
                                               href="javascript:;" class="btn btn-sm btn-danger delete_settings"><i
                                                        class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style=" {{ $errors->has('modification_reason') ? ' has-error' : 'display: none' }}"
                                    class="add_more_modification_form">
                                    <td>{{ count($modication_reason) + 1 }}</td>
                                    <td>
                                        <form action="{{ route('settings.leaseclassification.addleasemodificationreason') }}"
                                              method="POST" class="add_more_modification_reason_form">
                                            {{ csrf_field() }}
                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ old('title') }}" name="title"
                                                       placeholder="Title"
                                                       class="form-control {{ $errors->has('title') ? ' has-error' : '' }}"/>
                                                @if ($errors->has('title'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('title') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                                onclick="javascript:$('.add_more_modification_reason_form').submit();"
                                                class="btn btn-sm btn-success" title="Save"><i
                                                    class="fa fa-check-square"></i></button>
                                        <a href="javascript:;" class="btn btn-sm btn-danger add_more"
                                           data-form="add_more_modification_reason" title="Cancel"><i
                                                    class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Categories of Lease Assets Excluded from Valuation
                        </div>

                        <div class="panel-body settingTble">
                            <table class="table table-condensed">
                                <thead>
                                <tr>
                                    <th width="50px">Sr No.</th>
                                    <th>Title</th>

                                </tr>

                                </thead>

                                <tbody>
                                @foreach($category_excluded as $key=> $value)

                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="title">
                                            {{ $value->leaseassetcategories->title }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>3</td>
                                    @php
                                        $is_disabled = false;
                                    @endphp
                                    @if(count($category_excluded_id) > 2 )
                                        @php
                                            $is_disabled = true;
                                        @endphp
                                    @endif
                                    <td>Intangible Asset
                                        @if($is_disabled)
                                            @if(collect($category_excluded_all)->where('status', '=', '0')->where('category_id', '=', '7')->count() > 0)
                                                <span class="badge badge-success">Excluded
                                                in Lease Valuation Capitalization</span>
                                            @else
                                                <span class="badge badge-success">Included in Lease
                                                Valuation Capitalization</span>
                                            @endif
                                            {{--<a href="javascript:;"--}}
                                               {{--class="btn btn-sm btn-success add_intangible_asset disabled">Include in Lease--}}
                                                {{--Valuation Capitalization</a>--}}

                                            {{--<a href="javascript:;" class="btn btn-sm btn-danger delete_settings disabled">Exclude--}}
                                                {{--in Lease Valuation Capitalization</a>--}}
                                        @else
                                            <a data-href="{{ route('settings.leaseclassification.addcategoriesexcluded', ['id' => 7]) }}"
                                               href="javascript:;"
                                               class="btn btn-sm btn-success add_intangible_asset" data-status="1">Include in Lease
                                                Valuation Capitalization</a>
                                            <a data-href="{{ route('settings.leaseclassification.addcategoriesexcluded', ['id' => 7]) }}"
                                               href="javascript:;" class="btn btn-sm btn-danger add_intangible_asset" data-status="0">Exclude
                                                in Lease Valuation Capitalization</a>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Settings Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">

            </div>
        </div>
    </div>

@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
@endsection