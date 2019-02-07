<form role="form" class="form-horizontal" method="post">
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

    <div class="form-group{{ $errors->has('lease_start_date') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Lease Start Date</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="lease_start_date" id="lease_start_date" type="text" autocomplete="off"
                   value="{{ \Carbon\Carbon::parse(old('lease_start_date', $asset->lease_start_date))->format(config('settings.date_format'))}}" readonly="off" @if($subsequent_modify_required) disabled="disabled" @endif>
            @if ($errors->has('lease_start_date'))
                <span class="help-block">
                    <strong>{{ $errors->first('lease_start_date') }}</strong>
                </span>
            @endif

            @if($subsequent_modify_required)
                <input type="hidden" value="{{ $asset->lease_start_date }}" name="lease_start_date">
            @endif

        </div>
    </div>
    <div class="form-group{{ $errors->has('lease_end_date') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Lease End Date</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="lease_end_date" id="lease_end_date" type="text" autocomplete="off"
                   value="{{ \Carbon\Carbon::parse(old('lease_end_date', $model->lease_end_date))->format(config('settings.date_format'))}}" readonly="off">
            @if ($errors->has('lease_end_date'))
                <span class="help-block">
                    <strong>{{ $errors->first('lease_end_date') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('lease_contract_duration_id') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Lease Classified</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <select name="lease_contract_duration_id" class="form-control" readonly="readonly">
                @foreach($lease_contract_duration as $item)
                    <option value="{{ $item->id }}"
                            @if(old('lease_contract_duration_id', $model->lease_contract_duration_id) == $item->id) selected="selected" @endif>{{ $item->title }}</option>
                @endforeach
            </select>
            @if ($errors->has('lease_classified'))
                <span class="help-block">
                    <strong>{{ $errors->first('lease_classified') }}</strong>
                </span>
            @endif
        </div>
    </div>


    <div class="form-group btnMainBx">
        <div class="col-md-6 col-sm-6 btn-backnextBx">

            <a href="{{ $back_button}}" class="btn btn-danger">Back</a>
            @if($asset->leaseDurationClassified)
                <a href="{{ route('lease.escalation.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
            @endif

        </div>
        <div class="col-md-6 col-sm-6 btnsubmitBx">

            <button type="submit" class="btn btn-success">
                Save
            </button>
        </div>
    </div>

</form>
@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
@endsection