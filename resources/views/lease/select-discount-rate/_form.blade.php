<form role="form"  class="form-horizontal" method="post" enctype="multipart/form-data">
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

    <div class="form-group{{ $errors->has('interest_rate') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Interest Rate Implicit in the Lease</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="interest_rate" id="yes" type="text" value="{{ old('interest_rate', $model->interest_rate) }}" >
             @if ($errors->has('interest_rate'))
                <span class="help-block">
                    <strong>{{ $errors->first('interest_rate') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('annual_average_esclation_rate') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Annual Average Escalation Rate</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="annual_average_esclation_rate" id="yes" type="text" value="{{ old('annual_average_esclation_rate', $model->annual_average_esclation_rate) }}" >
             @if ($errors->has('annual_average_esclation_rate'))
                <span class="help-block">
                    <strong>{{ $errors->first('annual_average_esclation_rate') }}</strong>
                </span>
            @endif
        </div>
    </div>
     <div class="form-group{{ $errors->has('discount_rate_to_use') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Select Discount Rate to Use</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="discount_rate_to_use" id="yes" type="text" value="{{ old('discount_rate_to_use', $model->discount_rate_to_use) }}" >
             @if ($errors->has('discount_rate_to_use'))
                <span class="help-block">
                    <strong>{{ $errors->first('discount_rate_to_use') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group btnMainBx">
        <div class="col-md-6 col-sm-6 btn-backnextBx">

            <a href="{{ route('addlease.lowvalue.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
            @if($asset->leaseSelectDiscountRate)
                <a href="{{ route('addlease.balanceasondec.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
            @endif

        </div>
        <div class="col-md-6 col-sm-6 btnsubmitBx">
            <button type="submit" name="submit" class="btn btn-success">
                Save
            </button>
        </div>
    </div>

</form>

@section('footer-script')

@endsection