<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('lease.payments.saveassetpayment', ['id' => $asset->id, 'total_payments'=>$total_payments]) }}">
    {{ csrf_field() }}
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Type of Lease Payments</legend>

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-4 control-label">Name of Lease Payment</label>
            <div class="col-md-6">
                <input id="name" type="text" placeholder="Name" class="form-control" name="name" value="{{ old('name', $payment->name) }}">
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }} required">
            <label for="type" class="col-md-4 control-label">Type of Lease Payment</label>
            <div class="col-md-6">
                <select name="type" class="form-control">
                    <option value="">--Select Lease Payment Type--</option>
                    @foreach($lease_payments_types as $lease_payments_type)
                        <option value="{{ $lease_payments_type->id }}" @if(old('type', $payment->type) == $lease_payments_type->id) selected="selected" @endif>{{ $lease_payments_type->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
            <label for="type" class="col-md-4 control-label">Nature of Lease Payment</label>
            <div class="col-md-6">
                <select name="nature" class="form-control">
                    <option value="">--Select Lease Payment Nature--</option>
                    @foreach($lease_payments_nature as $nature)
                        <option value="{{ $nature->id}}" @if(old('nature',$payment->nature) == $nature->id) selected="selected" @endif>{{ $nature->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('nature'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nature') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('variable_basis') ? ' has-error' : '' }} required variable_basis" style="display: none">
            <label for="variable_basis" class="col-md-4 control-label">Variable Basis</label>
            <div class="col-md-6">
                <input id="variable_basis" type="text" placeholder="Name" class="form-control" name="variable_basis" value="{{ old('variable_basis',$payment->variable_basis) }}">
                @if ($errors->has('variable_basis'))
                    <span class="help-block">
                        <strong>{{ $errors->first('variable_basis') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('variable_amount_determinable') ? ' has-error' : '' }} required variable_basis" style="display: none">
            <label for="variable_amount_determinable" class="col-md-4 control-label">Variable Amount Determinable</label>
            <div class="col-md-6">

                <div class="col-md-6 form-check form-check-inline">
                    <input class="form-check-input" name="variable_amount_determinable" type="checkbox" id="yes" value="yes" @if(old('variable_amount_determinable',$payment->variable_amount_determinable) == "yes") checked="checked" @endif>
                    <label class="form-check-label" for="yes" style="vertical-align: 4px">Yes</label>
                </div>

                <div class=" col-md-6 form-check form-check-inline">
                    <input class="form-check-input" name="variable_amount_determinable" type="checkbox" id="no" value="no" @if(old('variable_amount_determinable',$payment->variable_amount_determinable) == "no") checked="checked" @endif>
                    <label class="form-check-label" for="no" style="vertical-align: 4px">No</label>
                </div>
            </div>
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            <label for="description" class="col-md-4 control-label">Any Other Description</label>
            <div class="col-md-6">
                <input id="description" type="text" placeholder="Description" class="form-control" name="description" value="{{ old('description',$payment->description) }}">
                @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    </fieldset>

    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Lease Payment Periods</legend>
        <div class="form-group{{ $errors->has('payment_interval') ? ' has-error' : '' }} required">
            <label for="payment_interval" class="col-md-4 control-label">Lease Payment Interval</label>
            <div class="col-md-6">
                <select name="payment_interval" class="form-control">
                    <option value="">--Select Payment Interval--</option>
                    @foreach($payments_frequencies as $frequency)
                        <option value="{{ $frequency->id }}" @if(old('payment_interval',$payment->payment_interval) == $frequency->id) selected="selected" @endif>{{ $frequency->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('payment_interval'))
                    <span class="help-block">
                        <strong>{{ $errors->first('payment_interval') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('payout_time') ? ' has-error' : '' }} required">
            <label for="payout_time" class="col-md-4 control-label">Lease Payment Payout Time</label>
            <div class="col-md-6">
                <select name="payout_time" class="form-control">
                    <option value="">--Select Payment Payout Time--</option>
                    @foreach($payments_payout_times as $payout_time)
                        <option value="{{ $payout_time->id }}" @if(old('payout_time',$payment->payout_time) == $payout_time->id) selected="selected" @endif>{{ $payout_time->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('payout_time'))
                    <span class="help-block">
                        <strong>{{ $errors->first('payout_time') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('first_payment_start_date') ? ' has-error' : '' }} required">
            <label for="first_payment_start_date" class="col-md-4 control-label">First Lease Payment Start Date</label>
            <div class="col-md-6">
                <input id="first_payment_start_date" type="text" placeholder="First Lease Payment Start Date" class="form-control" name="first_payment_start_date" value="{{ old('first_payment_start_date',$payment->first_payment_start_date) }}">
                @if ($errors->has('first_payment_start_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('first_payment_start_date') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('last_payment_end_date') ? ' has-error' : '' }} required">
            <label for="last_payment_end_date" class="col-md-4 control-label">Last Lease Payment End Date</label>
            <div class="col-md-6">
                <input id="last_payment_end_date" type="text" placeholder="Last Lease Payment End Date" class="form-control" name="last_payment_end_date" value="{{ old('last_payment_end_date',$payment->last_payment_end_date) }}">
                @if ($errors->has('last_payment_end_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('last_payment_end_date') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    </fieldset>

    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Lease Payments</legend>
        <div class="form-group{{ $errors->has('payment_currency') ? ' has-error' : '' }} required">
            <label for="payment_currency" class="col-md-4 control-label">Lease Payment Currency</label>
            <div class="col-md-6">
                <input id="payment_currency" type="text" placeholder="Lease Payment Currency" class="form-control" name="payment_currency" value="{{ $lease->contractCurrency->code }}" readonly="readonly">
                @if ($errors->has('payment_currency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('payment_currency') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('variable_amount_determinable') ? ' has-error' : '' }} required using_lease_payment" style="display: none">
            <label for="variable_amount_determinable" class="col-md-4 control-label">Using Lease Payment</label>
            <div class="col-md-6">

                <div class="col-md-6 form-check form-check-inline">
                    <input class="form-check-input" name="using_lease_payment" type="checkbox" id="yes" value="1" @if(old('using_lease_payment' ,$payment->using_lease_payment) == "1") checked="checked" @endif>
                    <label class="form-check-label" for="1" style="vertical-align: 4px">Current Lease Payment as on Jan 01, 2019</label>
                </div>

                <div class=" col-md-6 form-check form-check-inline">
                    <input class="form-check-input" name="using_lease_payment" type="checkbox" id="no" value="2" @if(old('using_lease_payment',$payment->using_lease_payment) == "2") checked="checked" @endif>
                    <label class="form-check-label" for="2" style="vertical-align: 4px">Initial Lease Payment as on First Lease Start</label>
                </div>
            </div>
        </div>

        <div class="form-group{{ $errors->has('similar_chateristics_assets') ? ' has-error' : '' }} required">
            <label for="similar_chateristics_assets" class="col-md-4 control-label">Number of Units of Lease Assets of Similar Characteristics</label>
            <div class="col-md-6">
                <input id="similar_chateristics_assets" type="text" placeholder="Number of Units of Lease Assets of Similar Characteristics" class="form-control" name="similar_chateristics_assets" value="{{ $asset->similar_asset_items }}" readonly="readonly">
                @if ($errors->has('similar_chateristics_assets'))
                    <span class="help-block">
                        <strong>{{ $errors->first('similar_chateristics_assets') }}</strong>
                    </span>
                @endif
            </div>
        </div>


        <div class="form-group{{ $errors->has('payment_per_interval_per_unit') ? ' has-error' : '' }} required">
            <label for="payment_per_interval_per_unit" class="col-md-4 control-label">Lease Payment Per Interval Per Unit</label>
            <div class="col-md-6">
                <input id="payment_per_interval_per_unit" type="text" placeholder="" class="form-control" name="payment_per_interval_per_unit" value="{{ old('payment_per_interval_per_unit',$payment->payment_per_interval_per_unit) }}">
                @if ($errors->has('payment_per_interval_per_unit'))
                    <span class="help-block">
                        <strong>{{ $errors->first('payment_per_interval_per_unit') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('total_amount_per_interval') ? ' has-error' : '' }} required">
            <label for="total_amount_per_interval" class="col-md-4 control-label">Total Lease Amount Per Interval</label>
            <div class="col-md-6">
                <input id="total_amount_per_interval" type="text" placeholder="" class="form-control" name="total_amount_per_interval" value="{{ old('total_amount_per_interval',$payment->total_amount_per_interval) }}">
                @if ($errors->has('total_amount_per_interval'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_amount_per_interval') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('attachment') ? ' has-error' : '' }} required">
            <label for="workings_doc" class="col-md-4 control-label">Upload Any Workings</label>
            <div class="col-md-6">
                <input id="workings_doc" type="file" placeholder="" class="form-control" name="attachment">
                @if ($errors->has('attachment'))
                    <span class="help-block">
                        <strong>{{ $errors->first('attachment') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    </fieldset>


    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <a href="{{ route('addlease.payments.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>

            <button type="submit" class="btn btn-success">
                Save As Draft
            </button>

            <button type="submit" class="btn btn-primary next_submit">
                Save & Next
            </button>

        </div>

    </div>


</form>