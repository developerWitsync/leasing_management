<form role="form" class="form-horizontal">

    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Type of Lease Payments</legend>

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-4 control-label">Name of Lease Payment</label>
            <div class="col-md-6">
                <input id="name" type="text" placeholder="Name" class="form-control" name="name" value="{{ old('name') }}">
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
                        <option value="{{ $lease_payments_type->id}}">{{ $lease_payments_type->title }}</option>
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
                        <option value="{{ $nature->id}}">{{ $nature->title }}</option>
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
                <input id="variable_basis" type="text" placeholder="Name" class="form-control" name="variable_basis">
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
                    <input class="form-check-input" name="variable_amount_determinable" type="checkbox" id="yes" value="yes">
                    <label class="form-check-label" for="yes" style="vertical-align: 4px">Yes</label>
                </div>

                <div class=" col-md-6 form-check form-check-inline">
                    <input class="form-check-input" name="variable_amount_determinable" type="checkbox" id="no" value="no">
                    <label class="form-check-label" for="no" style="vertical-align: 4px">No</label>
                </div>
            </div>
        </div>


        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            <label for="description" class="col-md-4 control-label">Any Other Description</label>
            <div class="col-md-6">
                <input id="description" type="text" placeholder="Description" class="form-control" name="description">
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
                        <option value="{{ $frequency->id }}">{{ $frequency->title }}</option>
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
                        <option value="{{ $payout_time->id }}">{{ $payout_time->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('payout_time'))
                    <span class="help-block">
                        <strong>{{ $errors->first('payout_time') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }} required">
            <label for="start_date" class="col-md-4 control-label">First Lease Payment Start Date</label>
            <div class="col-md-6">
                <input id="start_date" type="text" placeholder="First Lease Payment Start Date" class="form-control" name="start_date">
                @if ($errors->has('start_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('start_date') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    </fieldset>

</form>