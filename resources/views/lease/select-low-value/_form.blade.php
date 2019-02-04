<form role="form"  class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="fair_market_value" value="{{$asset->fairMarketValue->total_units}}">
    <div class="form-group{{ $errors->has('undiscounted_lease_payment') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Undiscounted Lease Payments</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="undiscounted_lease_payment" id="yes" type="text" value="{{ $total_undiscounted_value }}" readonly="readonly" >
             @if ($errors->has('undiscounted_lease_payment'))
                <span class="help-block">
                    <strong>{{ $errors->first('undiscounted_lease_payment') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('is_classify_under_low_value') ? ' has-error' : '' }} required">
        <label for="type" class="col-md-4 control-label">Classify under Low Value Lease Asset</label>
            <div class="col-md-6">
            <input class="form-check-input" name="is_classify_under_low_value" type="checkbox" id="is_classify_under_low_value_yes" value="yes" @if(old('is_classify_under_low_value', $model->is_classify_under_low_value)  == "yes") checked="checked" @endif >
            <label clas="form-check-label" for="is_classify_under_low_value_yes" style="vertical-align: 4px">Yes</label><br>
            <input class="form-check-input" name="is_classify_under_low_value" type="checkbox" id="is_classify_under_low_value_no" value="no" @if(old('is_classify_under_low_value', $model->is_classify_under_low_value)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="is_classify_under_low_value	_no" style="vertical-align: 4px">No</label>

            @if ($errors->has('is_classify_under_low_value'))
                    <span class="help-block">
                        <strong>{{ $errors->first('is_classify_under_low_value') }}</strong>
                    </span>
                @endif
            </div>
    </div>
    <div class="hidden-group" id="hidden-fields" @if(old('is_classify_under_low_value',$model->is_classify_under_low_value ) == "yes") style="display:block;" @else  style="display:none;" @endif>
    <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Provide Reasons for Selection to Low Value Asset</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="reason" id="yes" type="text" value="{{ old('reason', $model->reason) }}" >
             @if ($errors->has('reason'))
                <span class="help-block">
                    <strong>{{ $errors->first('reason') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
  <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <a href="{{ route('addlease.lowvalue.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
            <button type="submit" name="submit" class="btn btn-success">
                Submit
            </button>
            @if($asset->leaseSelectLowValue)
                <a href="{{ route('addlease.discountrate.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
            @endif
        </div>
  </div>

</form>

@section('footer-script')
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
<script type="text/javascript">
        $(document).on('click', 'input[name="is_classify_under_low_value"]', function() {
            $('input[name="is_classify_under_low_value"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == 'yes') {
                $("#is_classify_under_low_value	_no").prop('checked', false);
                $('#hidden-fields').show();
                 bootbox.confirm({
                    message: "Are you sure of your selection,since once selected can not be changed if any subsequent remeasurement level",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        
                    }
                });
            } else {
                $("#is_classify_under_low_value	_yes").prop('checked', false);
                $('#hidden-fields').hide();
            }

            
        });
</script>

@endsection