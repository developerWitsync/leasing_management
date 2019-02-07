<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">
    <div class="form-group required">
        <label for="uuid" class="col-md-12 control-label">ULA Code</label>
        <div class="col-md-12 form-check form-check-inline">
            <input type="text" value="{{ $asset->uuid}}" class="form-control" id="uuid" name="uuid" disabled="disabled">
        </div>
    </div>

    <div class="form-group required">
        <label for="asset_name" class="col-md-12 control-label">Asset Name</label>
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

    <div class="form-group{{ $errors->has('lease_termination_option_available') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-12 control-label">Lease Termination Option Available Under the Contract</label>
        <div class="col-md-12 form-check form-check-inline mrktavail" required>
            <span><input class="form-check-input" name="lease_termination_option_available" id="yes" type="checkbox"
                   value="yes"
                   @if(old('lease_termination_option_available', $model->lease_termination_option_available) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
            </span>
            <span>
            <input class="form-check-input" name="lease_termination_option_available" id="no" type="checkbox" value="no"
                   @if(old('lease_termination_option_available', $model->lease_termination_option_available)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            </span>
            @if ($errors->has('lease_termination_option_available'))
                <span class="help-block">
                    <strong>{{ $errors->first('lease_termination_option_available') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="hidden-group" id="hidden-field"
         @if(old('lease_termination_option_available', $model->lease_termination_option_available) == "yes") style="display:block;"
         @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('exercise_termination_option_available') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-12 control-label">Reasonable Certainity to Exercise Termination Option as of
                today</label>
            <div class="col-md-12 form-check form-check-inline mrktavail" required>
                <span><input class="form-check-input" name="exercise_termination_option_available" id="yes" type="checkbox"
                       value="yes"
                       @if(old('exercise_termination_option_available', $model->exercise_termination_option_available) == "yes") checked="checked" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
                </span>
                <span>
                    <input class="form-check-input" name="exercise_termination_option_available" id="no" type="checkbox"
                       value="no"
                       @if(old('exercise_termination_option_available', $model->exercise_termination_option_available)  == "no") checked="checked" @endif>
                    <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
                </span>
                    @if ($errors->has('exercise_termination_option_available'))
                    <span class="help-block">
                        <strong>{{ $errors->first('exercise_termination_option_available') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="hidden-group" id="hidden-elements"
         @if(old('exercise_termination_option_available',$model->exercise_termination_option_available) == "yes" && old('lease_termination_option_available', $model->lease_termination_option_available) == "yes") style="display:block;"
         @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('lease_end_date') ? ' has-error' : '' }} required">
            <label for="lease_end_date" class="col-md-12 control-label">Expected Lease End Date</label>
            <div class="col-md-12">
                <input type="text" placeholder="Expected Lease End Date" class="form-control" id="lease_end_date"
                       name="lease_end_date" value="{{ old('lease_end_date', $model->lease_end_date) }}">
                @if ($errors->has('lease_end_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lease_end_date') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('termination_penalty_applicable') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-12 control-label">Termination Penalty Applicable</label>
            <div class="col-md-12 form-check form-check-inline mrktavail" required>
                <span><input class="form-check-input" name="termination_penalty_applicable" id="yes" type="checkbox"
                       value="yes"
                       @if(old('termination_penalty_applicable', $model->termination_penalty_applicable) == "yes") checked="checked" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
                </span>
                <span><input class="form-check-input" name="termination_penalty_applicable" id="no" type="checkbox" value="no"
                       @if(old('termination_penalty_applicable', $model->termination_penalty_applicable)  == "no") checked="checked" @endif>
                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
                </span>
                @if ($errors->has('termination_penalty_applicable'))
                    <span class="help-block">
                        <strong>{{ $errors->first('termination_penalty_applicable') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="hidden-group" id="hidden-fields"
         @if(old('termination_penalty_applicable',$model->termination_penalty_applicable) == "yes" && old('exercise_termination_option_available',$model->exercise_termination_option_available ) == "yes" && old('lease_termination_option_available', $model->lease_termination_option_available) == "yes") style="display:block;"
         @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} required">
            <label for="currency" class="col-md-12 control-label">Currency</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $lease->lease_contract_id }}" class="form-control" id="currency"
                       name="currency" readonly="readonly">
                @if ($errors->has('currency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('termination_penalty') ? ' has-error' : '' }} required">
            <label for="termination_penalty" class="col-md-12 control-label">Termination Penalty</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" class="form-control" id="termination_penalty" name="termination_penalty"
                       value="{{ old('termination_penalty', $model->termination_penalty) }}">
                @if ($errors->has('termination_penalty'))
                    <span class="help-block">
                        <strong>{{ $errors->first('termination_penalty') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

    <div class="form-group btnMainBx">
        <div class="col-md-6 col-sm-6 btn-backnextBx">

            <a href="{{ route('addlease.leaseasset.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
            @if($asset->terminationOption)
                <a href="{{ route('addlease.renewable.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
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
    <script type="text/javascript">

        $(document).ready(function () {

                    @if(\Carbon\Carbon::parse($asset->accural_period)->greaterThanOrEqualTo(\Carbon\Carbon::today()))
            var minDate = new Date({{ $asset->accural_period }});
                    @else
            var minDate = new Date();
            @endif

            $("#lease_end_date").datepicker({
                dateFormat: "dd-M-yy",
                minDate: minDate,
                maxDate: new Date('{{ $asset->lease_end_date }}')
            });
        });

        $(document).on('click', 'input[name="lease_termination_option_available"]', function () {
            $('input[name="lease_termination_option_available"]').not(this).prop('checked', false);
            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-field').show();

            } else {
                $('#hidden-field').hide();
                $('#hidden-fields').hide();
                $('#hidden-elements').hide();
            }
        });
        $(document).on('click', 'input[name="exercise_termination_option_available"]', function () {
            $('input[name="exercise_termination_option_available"]').not(this).prop('checked', false);
            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-elements').show();
            } else {
                $('#hidden-elements').hide();
                $('#hidden-fields').hide();
            }
        });
        $(document).on('click', 'input[name="termination_penalty_applicable"]', function () {
            $('input[name="termination_penalty_applicable"]').not(this).prop('checked', false);
            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            } else {
                $('#hidden-fields').hide();
            }
        });

    </script>
@endsection