@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')

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



    <div class="panel panel-default">
        <div class="panel-heading">Lease Valuation</div>

        <div class="panel-body">
            @include('lease._subsequent_details')

            <div class="topInfo" style="padding-top: 12px;">
                <div>
                    Lease Asset : <span class="badge badge-primary">{{ $asset->name }}</span>
                </div>

                <div>
                    Lease Asset Classification : <span
                            class="badge badge-primary">{{ $asset->subcategory->title }}</span>
                </div>

                <div>
                    Currency : <span class="badge badge-primary">{{ $lease->lease_contract_id }}</span>
                </div>

                <div>
                    Lease Valuation as on Date : <span class="badge badge-primary">
                        @if($subsequent_modify_required)
                            {{ \Carbon\Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->format(config('settings.date_format')) }}
                        @else
                            @if($settings->date_of_initial_application == 2)
                                @if(\Carbon\Carbon::parse(getParentDetails()->baseDate->final_base_date)->subYear(1)->greaterThan(\Carbon\Carbon::parse($asset->lease_start_date)))
                                    {{ \Carbon\Carbon::parse(getParentDetails()->baseDate->final_base_date)->subYear(1)->format(config('settings.date_format')) }}
                                @else
                                    {{ \Carbon\Carbon::parse($asset->lease_start_date)->format(config('settings.date_format')) }}
                                @endif
                            @else
                                @if(\Carbon\Carbon::parse(getParentDetails()->baseDate->final_base_date)->greaterThan(\Carbon\Carbon::parse($asset->accural_period)))
                                    {{ \Carbon\Carbon::parse(getParentDetails()->baseDate->final_base_date)->format(config('settings.date_format')) }}
                                @else
                                    {{ \Carbon\Carbon::parse($asset->lease_start_date)->format(config('settings.date_format')) }}
                                @endif
                            @endif

                        @endif
                    </span>
                </div>

            </div>

            @include('lease.lease-valuation._lease_liability')

            @include('lease.lease-valuation._lease_valuation')

            @if($impairment_applicable)
                @include('lease.lease-valuation._impairment_test')
            @endif
        </div>
    </div>


    <div class="form-group btnMainBx">
        <div class="col-md-4 col-sm-4 btn-backnextBx">
            <a href="{{ $back_url }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
        </div>
        <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">

        </div>
        <div class="col-md-4 col-sm-4 btn-backnextBx rightlign ">
            <input type="hidden" name="action" value="">
            <a href="{{ route('addlease.leasepaymentinvoice.index', ['id' => $lease->id]) }}"
               class="btn btn-primary save_next"> {{ env('NEXT_LABEL') }} <i class="fa fa-arrow-right"></i></a>
        </div>

    </div>

    <!--Lease Liability Calculus -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content current_modal_body">

            </div>
        </div>
    </div>
    <!--Lease Liability Calculus-->

@endsection
@section('footer-script')
    <script src="{{ asset('js/pages/lease_valuation.js') }}"></script>
    <script>
        savePresentValueCalculus({{$asset->id}});
        @if($subsequent_modify_required)
            var existing_lease_liability = "{{$existing_lease_liability_balance}}";
            var existing_value_of_lease_asset = parseFloat("{{$existing_value_of_lease_asset}}");
            var existing_carrying_value_of_lease_asset = parseFloat("{{$existing_carrying_value_of_lease_asset}}");
        @endif
    </script>
@endsection