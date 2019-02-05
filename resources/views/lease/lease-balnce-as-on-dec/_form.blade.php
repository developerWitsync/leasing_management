<form role="form"  class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
   
    <div class="form-group{{ $errors->has('reporting_currency') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Reporting Currency</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <select  class="form-control" name="reporting_currency">
                <option value="">--Please Select</option>
                <option value="1" @if(old('reporting_currency', $model->reporting_currency) == "1") selected="selected" @endif>Statutory</option>
                <option value="2" @if(old('reporting_currency', $model->reporting_currency) == "2") selected="selected" @endif>Internal Currency</option>
            </select>
            @if ($errors->has('reporting_currency'))
                <span class="help-block">
                    <strong>{{ $errors->first('reporting_currency') }}</strong>
                </span>
            @endif
        </div>
    </div>
    @if($asset->accounting_treatment =='2')
    <div class="form-group{{ $errors->has('carrying_amount') ? ' has-error' : '' }} ">
        <label for="name" class="col-md-4 control-label">Carrying Amount</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="carrying_amount"  type="text" value="{{ old('carrying_amount', $model->carrying_amount) }}" >
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
            <input class="form-control" name="liability_balance" id="yes" type="text" value="{{ old('liability_balance', $model->liability_balance) }}" >
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
   
  <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <a href="{{ route('addlease.renewable.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
            <button type="submit" name="submit" class="btn btn-success">
                Submit
            </button>
            @if($asset->leaseBalanceAsOnDec)
                <a href="{{ route('addlease.initialdirectcost.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
            @endif
        </div>
  </div>

</form>

@section('footer-script')

@endsection