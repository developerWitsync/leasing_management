<div class="form-group{{ $errors->has('foreign_currency') ? ' has-error' : '' }} required">
    <label for="foreign_currency_1" class="col-md-6 control-label">Statutory Financial Reporting Currency</label>
    <div class="col-md-6">
        <select class="form-control" name="foreign_currency">
            <option value="">--Select Currency--</option>
            @foreach($currencies as $currency)
                <option value="{{ $currency->code }}">{{ $currency->code }}  {{ $currency->symbol }}</option>
            @endforeach
        </select>

        @if ($errors->has('foreign_currency_1'))
            <span class="help-block">
                <strong>{{ $errors->first('foreign_currency_1') }}</strong>
            </span>
        @endif
    </div>
</div>