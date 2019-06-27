<div class="panel panel-info">
    <div class="panel-heading">
        Reporting Currency
        {!! renderToolTip('Select the statutory financial reporting currency.', null, 'right') !!}
    </div>
    <div class="panel-body">
        <form class="form-horizontal" method="POST" action="{{ route('settings.currencies.save') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('statutory_financial_reporting_currency') ? ' has-error' : '' }} required">
                <label for="statutory_financial_reporting_currency" class="col-md-4 control-label">Statutory Financial Reporting Currency</label>
                <div class="col-md-6">
                    <select id="statutory_financial_reporting_currency" class="form-control" name="statutory_financial_reporting_currency" @if($reporting_currency_settings->statutory_financial_reporting_currency) readonly="readonly" @endif>
                        <option value="">--Select Currency--</option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->code }}" @if($currency->code == old('statutory_financial_reporting_currency', $reporting_currency_settings->statutory_financial_reporting_currency)) selected="selected" @endif>{{ $currency->code }}  {{ $currency->symbol }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('statutory_financial_reporting_currency'))
                        <span class="help-block">
                            <strong>{{ $errors->first('statutory_financial_reporting_currency') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{--<div class="form-group{{ $errors->has('internal_company_financial_reporting_currency') || $errors->has('internal_same_as_statutory_reporting') ? ' has-error' : '' }} required">--}}
                {{--<label for="internal_company_financial_reporting_currency" class="col-md-4 control-label">Internal Company Financial Reporting Currency</label>--}}
                {{--<div class="col-md-6">--}}
                    {{--<div class="input-group reportTble">--}}
                        {{--<div class="form-check">--}}
                            {{--<input class="form-check-input" @if(old('internal_same_as_statutory_reporting', $reporting_currency_settings->internal_same_as_statutory_reporting) == 'yes') checked="checked" @endif type="checkbox" name="internal_same_as_statutory_reporting" value="yes" id="same_as_statutory_reporting_yes">--}}
                            {{--<label class="form-check-label" for="same_as_statutory_reporting_yes">--}}
                                {{--Same as Statutory Reporting--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="form-check">--}}
                            {{--<input class="form-check-input" @if(old('internal_same_as_statutory_reporting', $reporting_currency_settings->internal_same_as_statutory_reporting) == 'no') checked="checked" @endif type="checkbox" name="internal_same_as_statutory_reporting" value="no" id="same_as_statutory_reporting_no">--}}
                            {{--<label class="form-check-label" for="same_as_statutory_reporting_no">--}}
                                {{--Different Currency--}}
                            {{--</label>--}}
                        {{--</div>--}}

                        {{--<div class="form-check internal_company_currency_select  @if(old('internal_same_as_statutory_reporting', $reporting_currency_settings->internal_same_as_statutory_reporting) == 'yes' || old('internal_same_as_statutory_reporting', $reporting_currency_settings->internal_same_as_statutory_reporting) == '') hidden @endif">--}}
                            {{--<select id="internal_company_financial_reporting_currency" class="form-control" name="internal_company_financial_reporting_currency">--}}
                                {{--<option value="">--Select Currency--</option>--}}
                                {{--@foreach($currencies as $currency)--}}
                                    {{--<option value="{{ $currency->code }}" @if($currency->code == old('internal_company_financial_reporting_currency',$reporting_currency_settings->internal_company_financial_reporting_currency)) selected="selected" @endif>{{ $currency->code }}  {{ $currency->symbol }}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}

                    {{--</div>--}}

                    {{--@if ($errors->has('internal_same_as_statutory_reporting'))--}}
                        {{--<span class="help-block">--}}
                            {{--<strong>{{ $errors->first('internal_same_as_statutory_reporting') }}</strong>--}}
                        {{--</span>--}}
                    {{--@endif--}}

                    {{--@if ($errors->has('internal_company_financial_reporting_currency'))--}}
                        {{--<span class="help-block">--}}
                            {{--<strong>{{ $errors->first('internal_company_financial_reporting_currency') }}</strong>--}}
                        {{--</span>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}


            <div class="form-group{{ $errors->has('currency_for_lease_reports') || $errors->has('lease_report_same_as_statutory_reporting') ? ' has-error' : '' }} required">
                <label for="currency_for_lease_reports" class="col-md-4 control-label">Currency for Lease Reports</label>
                <div class="col-md-6">
                    <div class="input-group reportTble">
                        <div class="form-check">
                            <input class="form-check-input" @if(old('lease_report_same_as_statutory_reporting', $reporting_currency_settings->lease_report_same_as_statutory_reporting) == '1') checked="checked" @endif type="checkbox" name="lease_report_same_as_statutory_reporting" value="1" id="lease_report_same_as_statutory_reporting_1" disabled="disabled" checked="checked">
                            <input type="hidden" name="lease_report_same_as_statutory_reporting" value="1">
                            <label class="form-check-label" for="lease_report_same_as_statutory_reporting_1">
                                Same as Statutory Reporting
                            </label>
                        </div>

                        {{--<div class="form-check">--}}
                            {{--<input class="form-check-input" @if(old('lease_report_same_as_statutory_reporting', $reporting_currency_settings->lease_report_same_as_statutory_reporting) == '2') checked="checked" @endif type="checkbox" name="lease_report_same_as_statutory_reporting" value="2" id="lease_report_same_as_statutory_reporting_2">--}}
                            {{--<label class="form-check-label" for="lease_report_same_as_statutory_reporting_2">--}}
                                {{--Same as Internal Reporting--}}
                            {{--</label>--}}
                        {{--</div>--}}

                        <div class="form-check">
                            <input class="form-check-input" @if(old('lease_report_same_as_statutory_reporting', $reporting_currency_settings->lease_report_same_as_statutory_reporting) == '3') checked="checked" @endif type="checkbox" name="lease_report_same_as_statutory_reporting" value="3" id="lease_report_same_as_statutory_reporting_3" disabled="disabled">
                            <label class="form-check-label" for="lease_report_same_as_statutory_reporting_3">
                                Different Currency
                            </label>
                        </div>

                        <div class="form-check currency_for_lease_reports  @if(old('lease_report_same_as_statutory_reporting', $reporting_currency_settings->lease_report_same_as_statutory_reporting) == '1' || old('lease_report_same_as_statutory_reporting', $reporting_currency_settings->lease_report_same_as_statutory_reporting) == '2' || old('lease_report_same_as_statutory_reporting', $reporting_currency_settings->lease_report_same_as_statutory_reporting) == '') hidden  @endif">
                            <select id="currency_for_lease_reports" class="form-control" name="currency_for_lease_reports">
                                <option value="">--Select Currency--</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->code }}" @if($currency->code == old('currency_for_lease_reports', $reporting_currency_settings->currency_for_lease_reports)) selected="selected" @endif>{{ $currency->code }}  {{ $currency->symbol }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    @if ($errors->has('lease_report_same_as_statutory_reporting'))
                        <span class="help-block">
                            <strong>{{ $errors->first('lease_report_same_as_statutory_reporting') }}</strong>
                        </span>
                    @endif

                    @if ($errors->has('currency_for_lease_reports'))
                        <span class="help-block">
                            <strong>{{ $errors->first('currency_for_lease_reports') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


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