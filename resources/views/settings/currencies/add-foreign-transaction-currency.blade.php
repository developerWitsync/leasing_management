@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        {{--<div class="panel-heading">Settings | Currencies Settings</div>--}}

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @include('settings._menubar')

            <div class="">
                <div role="tabpanel" class="tab-pane active">
                    <div class="panel panel-info">
                        <div class="panel-heading">Foreign Currency Transaction Settings</div>
                        <div class="panel-body">
                            <form method="post" class="form-horizontal">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('foreign_exchange_currency') ? ' has-error' : '' }} required">
                                    <label for="foreign_exchange_currency" class="col-md-4 control-label">Select Currencies of Transactions Dealing in</label>
                                    <div class="col-md-8">
                                        <select id="foreign_exchange_currency" class="form-control" name="foreign_exchange_currency">
                                            <option value="">--Select Currency--</option>
                                            @foreach($currencies as $currency)
                                                @if($currency->code != $reporting_currency_settings->statutory_financial_reporting_currency && $currency->code != $reporting_currency_settings->internal_company_financial_reporting_currency && $currency->code != $reporting_currency_settings->currency_for_lease_reports && !in_array($currency->code, $foreign_currencies))
                                                    <option value="{{ $currency->code }}" @if($currency->code == old('foreign_exchange_currency')) selected="selected" @endif>{{ $currency->code }}  {{ $currency->symbol }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('foreign_exchange_currency'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('foreign_exchange_currency') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{--<div class="form-group{{ $errors->has('exchange_rate') ? ' has-error' : '' }} required">--}}
                                    {{--<label for="exchange_rate" class="col-md-4 control-label">Exchange Rate</label>--}}
                                    {{--<div class="col-md-8">--}}
                                        {{--<input type="text" id="exchange_rate" value="{{ old('exchange_rate') }}" name="exchange_rate" class="form-control" placeholder="Exchange">--}}
                                        {{--@if ($errors->has('exchange_rate'))--}}
                                            {{--<span class="help-block">--}}
                                                {{--<strong>{{ $errors->first('exchange_rate') }}</strong>--}}
                                            {{--</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group{{ $errors->has('base_currency') ? ' has-error' : '' }}">
                                    <label for="foreign_exchange_currency" class="col-md-4 control-label">Base Currency</label>
                                    <div class="col-md-8">
                                        <select id="base_currency" class="form-control" name="base_currency" disabled="disabled">
                                            <option value="">--Select Currency--</option>
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->code }}" @if($currency->code == old('base_currency', $reporting_currency_settings->currency_for_lease_reports)) selected="selected" @endif>{{ $currency->code }}  {{ $currency->symbol }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('base_currency'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('base_currency') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{--<div class="form-group{{ $errors->has('valid_from') ? ' has-error' : '' }} required">--}}
                                    {{--<label for="valid_from" class="col-md-4 control-label">Exchange Rate Valid From</label>--}}
                                    {{--<div class="col-md-8">--}}
                                        {{--<input type="text" id="valid_from" value="{{ old('valid_from') }}" name="valid_from" class="form-control" placeholder="Exchange Rate Valid From">--}}
                                        {{--@if ($errors->has('valid_from'))--}}
                                            {{--<span class="help-block">--}}
                                                {{--<strong>{{ $errors->first('valid_from') }}</strong>--}}
                                            {{--</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group{{ $errors->has('valid_to') ? ' has-error' : '' }} required">--}}
                                    {{--<label for="valid_to" class="col-md-4 control-label">Exchange Rate Valid To</label>--}}
                                    {{--<div class="col-md-8">--}}
                                        {{--<input type="text" id="valid_to" value="{{ old('valid_to') }}" name="valid_to" class="form-control" placeholder="Exchange Rate Valid To">--}}
                                        {{--@if ($errors->has('valid_to'))--}}
                                            {{--<span class="help-block">--}}
                                                {{--<strong>{{ $errors->first('valid_to') }}</strong>--}}
                                            {{--</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-success">
                                            Save
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script>
        $(function() {

            $("#valid_from").datepicker({
                dateFormat: "dd-M-yy",
                minDate: 0,
                onSelect: function () {
                    var dt2 = $('#valid_to');
                    var startDate = $(this).datepicker('getDate');
                    //add 30 days to selected date
                    startDate.setDate(startDate.getDate() + 30);
                    var minDate = $(this).datepicker('getDate');
                    var dt2Date = dt2.datepicker('getDate');
                    //difference in days. 86400 seconds in day, 1000 ms in second
                    var dateDiff = (dt2Date - minDate)/(86400 * 1000);

                    //dt2 not set or dt1 date is greater than dt2 date
                    if (dt2Date == null || dateDiff < 0) {
                        dt2.datepicker('setDate', minDate);
                    }
                    //dt1 date is 30 days under dt2 date
                    else if (dateDiff > 30){
                        dt2.datepicker('setDate', startDate);
                    }
                    //sets dt2 maxDate to the last day of 30 days window
                    // dt2.datepicker('option', 'maxDate', startDate);
                    //first day which can be selected in dt2 is selected date in dt1
                    dt2.datepicker('option', 'minDate', minDate);
                }
            });
            $('#valid_to').datepicker({
                dateFormat: "dd-M-yy",
                minDate: 0
            });

        });
    </script>
@endsection