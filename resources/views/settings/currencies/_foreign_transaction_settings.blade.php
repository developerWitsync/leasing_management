<div class="panel panel-info">
    <div class="panel-heading">Foreign Currency Transaction Settings</div>
    <div class="panel-body">
        <div class="form-group{{ $errors->has('statutory_financial_reporting_currency') ? ' has-error' : '' }} required">
            <label for="statutory_financial_reporting_currency" class="col-md-6 control-label">Transactions in Foreign Currency Involved</label>
            <div class="col-md-6">

                    <div class="col-md-6 form-check form-check-inline">
                        <input class="form-check-input" name="is_involved" @if($reporting_currency_settings->is_foreign_transaction_involved == 'yes')  checked="checked" @endif type="checkbox" id="is_involved_yes" value="yes">
                        <label class="form-check-label" for="is_involved_yes" style="vertical-align: 4px">Yes</label>
                    </div>

                    <div class=" col-md-6 form-check form-check-inline">
                        <input class="form-check-input" name="is_involved" @if($reporting_currency_settings->is_foreign_transaction_involved == 'no')  checked="checked" @endif @if($exsist_froegincurrency >0)
disabled @endif type="checkbox" id="is_involved_no" value="no">
                        <label class="form-check-label" for="is_involved_no" style="vertical-align: 4px">No</label>
                    </div>
            </div>
        </div>
    </div>
</div>

@if($reporting_currency_settings->is_foreign_transaction_involved == "yes")
    <div class="panel panel-info add_foreign_transasction_currency" style="margin-top: 10px;">
        <div class="panel-heading">
            Select Currencies of Transactions Dealing in
            <span>
                <a href="{{ route('settings.currencies.addforeigntransactioncurrency') }}" class="btn btn-sm btn-primary pull-right add_more" data-form="add_more_form_lease_basis">Add More</a>
            </span>
        </div>
        <div class="panel-body">
            <div class="card-body settingTble">
                <table cellpadding="0" cellspacing="0" id="foreign_transaction_currency" class="table table-bordered table-hover display">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Foreign Transaction Currency</th>
                            <th>Base Currency</th>
                            <th>Rate</th>
                            <th>Valid From</th>
                            <th>Valid Till</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endif