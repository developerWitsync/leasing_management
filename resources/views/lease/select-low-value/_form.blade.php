<form role="form" class="form-horizontal" id="lease_low" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="categoriesOuter clearfix">
       
        <div class="form-group required">
            <label for="asset_name" class="col-md-12 control-label">Lease Asset Name</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $asset->name}}" class="form-control" id="asset_name" name="asset_name"
                       disabled="disabled">
            </div>
        </div>

        <div class="form-group required">
            <label for="asset_category" class="col-md-12 control-label">Lease Asset Classification</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $asset->category->title}}" class="form-control" id="asset_category"
                       name="asset_category" disabled="disabled">
            </div>
        </div>

        {{--<input type="hidden" name="fair_market_value" value="{{$asset->fairMarketValue->total_units}}">--}}
        <div class="form-group{{ $errors->has('undiscounted_lease_payment') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-12 control-label">Undiscounted Lease Payments</label>
            <div class="col-md-12 form-check form-check-inline" required>
                <input class="form-control" name="undiscounted_lease_payment" id="yes" type="text"
                       value="{{ $total_undiscounted_value }}" readonly="readonly">
                @if ($errors->has('undiscounted_lease_payment'))
                    <span class="help-block">
                    <strong>{{ $errors->first('undiscounted_lease_payment') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('is_classify_under_low_value') ? ' has-error' : '' }} required">
            <label for="type" class="col-md-12 control-label">Classify under Low Value Lease Asset</label>
            <div class="col-md-12 form-check form-check-inline">
                <span>
                    <input class="form-check-input" name="is_classify_under_low_value" type="checkbox"
                           id="is_classify_under_low_value_yes" value="yes"
                           @if(old('is_classify_under_low_value', $model->is_classify_under_low_value)  == "yes") checked="checked" @endif @if($subsequent_modify_required || $asset->specific_use == '2') disabled="disabled" @endif>
                    <label clas="form-check-label" for="is_classify_under_low_value_yes"
                           style="vertical-align: 4px">Yes</label>
                </span>
                <span>
                    <input class="form-check-input" name="is_classify_under_low_value" type="checkbox"
                           id="is_classify_under_low_value_no" value="no"
                           @if(old('is_classify_under_low_value', $model->is_classify_under_low_value)  == "no" || $asset->specific_use == '2') checked="checked" @endif @if($subsequent_modify_required || $asset->specific_use == '2') disabled="disabled" @endif>
                    <label class="form-check-label" for="is_classify_under_low_value	_no"
                           style="vertical-align: 4px">No</label>
                </span>

                @if ($errors->has('is_classify_under_low_value'))
                    <span class="help-block">
                        <strong>{{ $errors->first('is_classify_under_low_value') }}</strong>
                    </span>
                @endif

                @if($subsequent_modify_required)
                    <input type="hidden" name="is_classify_under_low_value" value="{{ $model->is_classify_under_low_value }}" />
                @endif

                @if($asset->specific_use == '2')
                    <input type="hidden" name="is_classify_under_low_value" value="no" />
                @endif

            </div>
        </div>
        <div class="hidden-group" id="hidden-fields"
             @if(old('is_classify_under_low_value',$model->is_classify_under_low_value ) == "yes") style="display:block;"
             @else  style="display:none;" @endif>
            <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }} required">
                <label for="name" class="col-md-12 control-label">Provide Reasons for Selection to Low Value
                    Asset</label>
                <div class="col-md-12 form-check form-check-inline" required>
                    <input class="form-control" name="reason" id="yes" type="text"
                           value="{{ old('reason', $model->reason) }}">
                    @if ($errors->has('reason'))
                        <span class="help-block">
                    <strong>{{ $errors->first('reason') }}</strong>
                </span>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <div class="form-group btnMainBx">
        <div class="col-md-4 col-sm-4 btn-backnextBx">
            <a href="{{ route('lease.escalation.index', ['id' => $lease->id]) }}" class="btn btn-danger"><i
                        class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
        </div>
        <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">
            <button type="submit" class="btn btn-success">
                {{ env('SAVE_LABEL') }} <i class="fa fa-download"></i></button>
        </div>
        <div class="col-md-4 col-sm-4 btn-backnextBx rightlign ">
            <input type="hidden" name="action" value="">
            <a href="javascript:void(0);" class="btn btn-primary save_next"> {{ env('NEXT_LABEL') }} <i
                        class="fa fa-arrow-right"></i></a>
        </div>

    </div>

</form>

@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', 'input[name="is_classify_under_low_value"]', function () {
            $('input[name="is_classify_under_low_value"]').not(this).prop('checked', false);

            if ($(this).is(':checked') && $(this).val() == 'yes') {
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
        $('.save_next').on('click', function (e) {
            e.preventDefault();
            $('input[name="action"]').val('next');
            $('#lease_low').submit();
        });
    </script>

@endsection