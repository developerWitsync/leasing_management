<form role="form"  class="form-horizontal" method="post" enctype="multipart/form-data" id="lease_balence">
    {{ csrf_field() }}

    <div class="form-group required">
        <label for="uuid" class="col-md-4 control-label">ULA Code</label>
        <div class="col-md-4 form-check form-check-inline">
            <input type="text" value="{{ $asset->uuid}}" class="form-control" id="uuid" name="uuid" disabled="disabled">
        </div>
    </div>

    <div class="form-group required">
        <label for="asset_name" class="col-md-4 control-label">Asset Name</label>
        <div class="col-md-4 form-check form-check-inline">
            <input type="text" value="{{ $asset->name}}" class="form-control" id="asset_name" name="asset_name" disabled="disabled">
        </div>
    </div>

    <div class="form-group required">
        <label for="asset_category" class="col-md-4 control-label">Lease Asset Classification</label>
        <div class="col-md-4 form-check form-check-inline">
            <input type="text" value="{{ $asset->category->title}}" class="form-control" id="asset_category" name="asset_category" disabled="disabled">
        </div>
    </div>

    <div class="form-group{{ $errors->has('reporting_currency') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Reporting Currency</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <select  class="form-control" name="reporting_currency">
                <option value="">--Please Select--</option>
                <option value="1" @if(old('reporting_currency', $model->reporting_currency) == "1") selected="selected" @endif>Statutory</option>
                <option value="2" @if(old('reporting_currency', $model->reporting_currency) == "2") selected="selected" @endif>Internal Currency</option>
                <option value="3" @if(old('reporting_currency', $model->reporting_currency) == "3") selected="selected" @endif>Lease Contract Currency</option>
            </select>
            <span class="badge badge-info selected_currency_option" style="display:none;">USD</span>
            <input type="hidden" name="reporting_currency_selected" value="{{ old('reporting_currency_selected', $model->reporting_currency_selected) }}"/>
            @if ($errors->has('reporting_currency'))
                <span class="help-block">
                    <strong>{{ $errors->first('reporting_currency') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('exchange_rate') ? ' has-error' : '' }} required">
        <label for="exchange_rate" class="col-md-4 control-label">Exchange Rate (as on 31 Dec 2018)</label>
        <div class="col-md-4 form-check form-check-inline">
            <input type="text" value="{{ old('exchange_rate', $model->exchange_rate) }}" class="form-control" id="exchange_rate" name="exchange_rate">
            @if ($errors->has('exchange_rate'))
                <span class="help-block">
                    <strong>{{ $errors->first('exchange_rate') }}</strong>
                </span>
            @endif
        </div>
    </div>

    @if($asset->accounting_treatment =='2')
    <div class="form-group{{ $errors->has('carrying_amount') ? ' has-error' : '' }} ">
        <label for="name" class="col-md-4 control-label">Carrying Amount</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="carrying_amount"  type="text" value="{{ old('carrying_amount', $model->carrying_amount) }}">
             @if ($errors->has('carrying_amount'))
                <span class="help-block">
                    <strong>{{ $errors->first('carrying_amount') }}</strong>
                </span>
            @endif
        </div>
    </div>
   
     <div class="form-group{{ $errors->has('liability_balance') ? ' has-error' : '' }} ">
        <label for="name" class="col-md-4 control-label">Liability Balance</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="liability_balance" id="yes" type="text" value="{{ old('liability_balance', $model->liability_balance) }}">
             @if ($errors->has('liability_balance'))
                <span class="help-block">
                    <strong>{{ $errors->first('liability_balance') }}</strong>
                </span>
            @endif
        </div>
    </div>
     @endif
    <div class="form-group{{ $errors->has('prepaid_lease_payment_balance') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Prepaid Lease Payment Balance</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="prepaid_lease_payment_balance"  type="text" value="{{ old('prepaid_lease_payment_balance', $model->prepaid_lease_payment_balance) }}" >
             @if ($errors->has('prepaid_lease_payment_balance'))
                <span class="help-block">
                    <strong>{{ $errors->first('prepaid_lease_payment_balance') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('accrued_lease_payment_balance') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Accrued lease Payment Balance</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="accrued_lease_payment_balance"  type="text" value="{{ old('accrued_lease_payment_balance', $model->accrued_lease_payment_balance) }}" >
             @if ($errors->has('accrued_lease_payment_balance'))
                <span class="help-block">
                    <strong>{{ $errors->first('accrued_lease_payment_balance') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('outstanding_lease_payment_balance') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">OutStanding Lease Payment Lease</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="outstanding_lease_payment_balance" type="text" value="{{ old('outstanding_lease_payment_balance', $model->outstanding_lease_payment_balance) }}" >
             @if ($errors->has('outstanding_lease_payment_balance'))
                <span class="help-block">
                    <strong>{{ $errors->first('outstanding_lease_payment_balance') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('any_provision_for_onerous_lease') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Any Provision for Onerous Lease</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="any_provision_for_onerous_lease" id="yes" type="text" value="{{ old('any_provision_for_onerous_lease', $model->any_provision_for_onerous_lease) }}" >
             @if ($errors->has('any_provision_for_onerous_lease'))
                <span class="help-block">
                    <strong>{{ $errors->first('any_provision_for_onerous_lease') }}</strong>
                </span>
            @endif
        </div>
    </div>
  <div class="form-group btnMainBx">
 <div class="col-md-4 col-sm-4 btn-backnextBx">
        <a href="{{ $back_url }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
    </div>
    <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">
        <button type="submit" class="btn btn-success"> 
        {{ env('SAVE_LABEL') }} <i class="fa fa-download"></i></button>
    </div>
    <div class="col-md-4 col-sm-4 btn-backnextBx rightlign ">
        <input type="hidden" name="action" value="">
        <a href="javascript:void(0);" class="btn btn-primary save_next"> {{ env('NEXT_LABEL') }} <i class="fa fa-arrow-right"></i></a>
    </div>
 
</div>
   <!--  <div class="form-group btnMainBx">
        <div class="col-md-6 col-sm-6 btn-backnextBx">

            <a href="{{ $back_url }}" class="btn btn-danger">Back</a>
            @if($asset->leaseBalanceAsOnDec)
                <a href="{{ route('addlease.initialdirectcost.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
            @endif

        </div>
        <div class="col-md-6 col-sm-6 btnsubmitBx">
            <button type="submit" class="btn btn-success">
                Save
            </button>
        </div>
    </div>
 -->
</form>

@section('footer-script')
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script type="text/javascript">
       
          $('.save_next').on('click', function (e) {
            
                e.preventDefault();
                $('input[name="action"]').val('next');
                $('#lease_balence').submit();
        });

    $('select[name="reporting_currency"]').on('change', function () {
        var _return_currency = '';
        var access_key = '{{ env("CURRENCY_API_ACCESS_KEY") }}';
        var base_date = '2018-12-31';
        var base = '{{ $currency_settings->statutory_financial_reporting_currency }}';
        var element_selector = 'input[name="exchange_rate"]';
        switch ($(this).val()) {
            case '1' :
                _return_currency = '{{ $currency_settings->statutory_financial_reporting_currency }}';
                $('.selected_currency_option').html(_return_currency).show();
                $('input[name="reporting_currency_selected"]').val(_return_currency);
                //call the exchange APIs from here to get the exchange rates
                fetchExchangeRate(base,_return_currency,base_date, access_key, element_selector);
                break;
            case '2' :
                _return_currency = '{{ $currency_settings->internal_company_financial_reporting_currency }}';
                $('.selected_currency_option').html(_return_currency).show();
                $('input[name="reporting_currency_selected"]').val(_return_currency);
                fetchExchangeRate(base,_return_currency,base_date, access_key, element_selector);
                break;
            case '3' :
                _return_currency = '{{ $currency_settings->currency_for_lease_reports }}';
                $('.selected_currency_option').html(_return_currency).show();
                $('input[name="reporting_currency_selected"]').val(_return_currency);
                fetchExchangeRate(base,_return_currency,base_date, access_key, element_selector);
                break;
            default :
                $('.selected_currency_option').html(_return_currency).hide();
                $('input[name="reporting_currency_selected"]').val(_return_currency);
                break;
        }
    });



</script>
@endsection